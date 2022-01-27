/*Query 1: Create a new patient account, together with email, password, name, date of birth, etc.*/

INSERT INTO patients(
        first_name
        , last_name
        , ssn
        , dob
        , phone
        , email
        , street_number
        , unit_number
        , street_name
        , zip_code
        , city
        , [state]
        , county
        , username
        , pwd
        , distance_preference) 
	VALUES(
        'John'
        , 'Jones'
        , '123456788'
        , '1964-02-20'
        , '2579027890'
        , 'johnjones@gmail.com'
        , '247'
        , '12L'
        , '35th st'
        , '10001'
        , 'Manhattan'
        , 'NY'
        , 'Manhattan'
        , 'john'
        , 'passwordJjones'	
        , 10);
    --Longitudes and Latitudes will be calculated once we insert the address using api in the second part of the project.
    --Group Id will be assigned in the second part of the project. 

 

/*Query 2: Insert a new appointment offered by a provider.*/

INSERT INTO available_slots (provider_id, slot_date, start_time, end_time) 
    VALUES (1, '2021-06-02', '09:45:00', '10:05:00');



/*Query 3: Write a query that, for a given patient, finds all available (not currently assigned) appointments that satisfy the
constraints on the patient's weekly schedule, sorted by increasing distance from the user's home address */

WITH max_distance AS
(
		SELECT  patients.patient_id,providers.provider_id,
					GEOGRAPHY::Point(patients.latitude, patients.longitude, 4326).STDistance(GEOGRAPHY::Point(providers.latitude, providers.longitude, 4326)) / 1609.344 as Distance
		FROM patients,providers 
		WHERE 
		patients.patient_id = 7 --For a given patient
),--distance between each patient AND provider
patient_elig_avail AS(
		SELECT patients.patient_id,patient_availability.day_of_the_week,blocktime.start_time,blocktime.end_time,priority_group.start_date as eligible_date 
		FROM patient_availability 
		JOIN blocktime 
		ON blocktime.blocktime_id = patient_availability.blocktime_id 
		JOIN patients  
		ON patients.patient_id = patient_availability.patient_id 
		--JOIN priority_group  
		--ON patients.group_id=priority_group.group_id
		WHERE 
		patients.patient_id = 7 --For a given patient
),--Get patient eligibility and availability
appointment_slots AS(
		SELECT *,FORMAT(slot_date,'dddd') AS week_day 
		FROM available_slots 
		WHERE appointment_id 
		NOT IN 
		(
			SELECT appointment_matches.appointment_id 
			FROM appointment_matches
			) 
),-- Get appointments that are not currently assigned
patient_slot_match AS(
		SELECT patient_id,appointment_id,appointment_slots.provider_id,appointment_slots.slot_date,appointment_slots.start_time,appointment_slots.end_time 
		FROM patient_elig_avail,appointment_slots 
		WHERE 
		patient_elig_avail.day_of_the_week = appointment_slots.week_day 
		AND 
		appointment_slots.slot_date> patient_elig_avail.eligible_date 
		AND 
		patient_elig_avail.start_time <= appointment_slots.start_time 
		AND 
		patient_elig_avail.end_time >= appointment_slots.end_time
		)
SELECT patients.patient_id,patient_slot_match.appointment_id,patient_slot_match.provider_id,patient_slot_match.slot_date,patient_slot_match.start_time,patient_slot_match.end_time 
FROM patients 
LEFT JOIN patient_slot_match 
ON patients.patient_id = patient_slot_match.patient_id 
LEFT JOIN max_distance 
ON max_distance.provider_id = patient_slot_match.provider_id 
AND 
max_distance.patient_id = patient_slot_match.patient_id 
WHERE 
patients.patient_id = 7 --For a given patient
ORDER BY 
max_distance.Distance ASC;





/*Query 4: For each priority group, list the number of patients that have already received the vaccination, the number of
patients currently scheduled for an appointment, AND the number of patients still waiting for an appointment.*/


WITH all_patient_matches (patient_id, match_status, group_id)  AS (SELECT p.patient_id, am.match_status, p.group_id
                                    FROM patients p
                                    LEFT OUTER JOIN appointment_matches am
                                    ON p.patient_id = am.patient_id),
vaccinated_patients AS (SELECT * 
                                    FROM all_patient_matches 
                                    WHERE match_status = 'completed'),
scheduled_patients  AS (SELECT * 
                                    FROM all_patient_matches 
                                    WHERE match_status = 'accepted'),

not_scheduled_patients  AS  (SELECT DISTINCT ns.patient_id, ns.group_id
                                    FROM ((SELECT patient_id, group_id
                                            FROM all_patient_matches
                                            WHERE NOT (match_status = 'accepted' OR match_status = 'completed'))
                                            UNION
                                            ( SELECT patient_id, group_id
                                            FROM all_patient_matches
                                            WHERE match_status IS NULL)) ns
                            ),

vaccinated_count AS     (  SELECT 
                            pg.group_id AS "GROUP ID"
                            , count(vp.patient_id) AS "# PATIENTS VACCINATED"

                        FROM 
                            priority_group pg 
                            FULL OUTER JOIN vaccinated_patients vp
                            ON pg.group_id = vp.group_id

                        GROUP BY
                            pg.group_id),

scheduled_count AS     (  SELECT 
                            pg.group_id AS "GROUP ID"
                            , count(sp.patient_id) AS "# PATIENTS SCHEDULED"

                        FROM 
                            priority_group pg 
                            FULL OUTER JOIN scheduled_patients sp
                            ON pg.group_id = sp.group_id

                        GROUP BY
                            pg.group_id),

not_scheduled_count AS     (  SELECT 
                            pg.group_id AS "GROUP ID"
                            , count(nsp.patient_id) AS "# PATIENTS NOT-SCHEDULED"

                        FROM 
                            priority_group pg 
                            FULL OUTER JOIN not_scheduled_patients nsp
                            ON pg.group_id = nsp.group_id

                        GROUP BY
                            pg.group_id)
   
SELECT vc.[GROUP ID]
    , [# PATIENTS VACCINATED]
    , [# PATIENTS SCHEDULED]
    , [# PATIENTS NOT-SCHEDULED]
FROM 
    vaccinated_count vc
    JOIN scheduled_count sc
    ON vc.[GROUP ID] = sc.[GROUP ID]

    JOIN not_scheduled_count nsc
    ON sc.[GROUP ID] = nsc.[GROUP ID]

ORDER BY
    vc.[GROUP ID] ASC
;



/*Query 5: For each patient, output the ID, name, and date when the patient becomes eligible for vaccination.*/

SELECT patient_id,first_name+' '+last_name as Patient_Name, start_date as Eligibility_Date 
FROM patients 
JOIN priority_group 
ON patients.group_id = priority_group.group_id OR patients.group_id IS NULL;



/*Query 6: Output the ID and name of all patients that have cancelled at least 3 appointments, or that did not show up for
at least two confirmed appointments that they did not cancel.*/

SELECT 
    first_name  AS "First Name"
    , last_name AS "Last Name"
    , group_id  AS "Priority Group"
    , dob       AS "Date of Birth"
    , phone     AS "Phone"
    , email     AS "E-mail"

FROM
    patients p
    JOIN
        ((SELECT am.patient_id
        FROM
            appointment_matches am
        WHERE
            match_status = 'canceled'
        GROUP BY 
            am.patient_id
        HAVING
            COUNT(am.match_status) >= 3
        )
        UNION
        (SELECT am.patient_id
        FROM
            appointment_matches am
        WHERE
            match_status = 'missed'
        GROUP BY 
            am.patient_id
        HAVING
            COUNT(am.match_status) >= 2
        )) missed_and_canceled
    ON p.patient_id = missed_and_canceled.patient_id
ORDER BY
    group_id ASC
;



/*Query 7: Output the ID and name of the provider(s) that has performed the largest number of vaccinations.*/
WITH countOfcompletedVaccination as(
        SELECT provider_id,count(*) AS COUNT_OF_COMPLETED 
        FROM appointment_matches 
        JOIN 
	    available_slots 
        ON appointment_matches.appointment_id = available_slots.appointment_id 
        WHERE 
	    match_status = 'completed'  
        GROUP BY provider_id
	)
SELECT providers.provider_id,providers.provider_name 
FROM countOfcompletedVaccination 
JOIN 
providers
ON providers.provider_id = countOfcompletedVaccination.provider_id
WHERE 
COUNT_OF_COMPLETED = (
						SELECT MAX(COUNT_OF_COMPLETED) 
						FROM countOfcompletedVaccination
						);
