CREATE PROCEDURE AppointmentMatchingPrd @group int
AS
WITH max_distance AS
(
		SELECT  patients.patient_id,providers.provider_id,
					GEOGRAPHY::Point(patients.latitude, patients.longitude, 4326).STDistance(GEOGRAPHY::Point(providers.latitude, providers.longitude, 4326)) / 1609.344 as Distance
		FROM patients,providers
		WHERE 
		patients.group_id=@group
),--distance between each patient AND provider
patient_elig_avail AS(
		SELECT patients.patient_id,patient_availability.day_of_the_week,blocktime.start_time,blocktime.end_time,priority_group.start_date as eligible_date 
		FROM patient_availability 
		JOIN blocktime 
		ON blocktime.blocktime_id = patient_availability.blocktime_id 
		JOIN patients  
		ON patients.patient_id = patient_availability.patient_id 
		AND
		patients.group_id = @group
		JOIN priority_group  
		ON patients.group_id=priority_group.group_id
	
		
),--Get patient eligibility and availability
appointment_slots AS(
		SELECT *,FORMAT(slot_date,'dddd') AS week_day 
		FROM available_slots 
		WHERE appointment_id 
		NOT IN 
		(
			SELECT appointment_matches.appointment_id 
			FROM appointment_matches
			WHERE match_status
			IN ('missed','completed','accepted')
			) 
),-- Get appointments that are not currently assigned
appointmentDclCnclPairs AS(
		SELECT appointment_id, patient_id -- 7,4 (declined) 8,3(canceled)
		FROM 
		appointment_matches
		WHERE
		match_status
		IN ('declined','canceled')
),
removePatientsAcceptedCompleted AS(
SELECT patient_id -- 7,4 (declined) 8,3(canceled)
		FROM 
		appointment_matches
		WHERE
		match_status
		IN ('accepted','completed')
),
patient_slot_match AS(
		SELECT patient_id,appointment_id,appointment_slots.provider_id,appointment_slots.slot_date,appointment_slots.start_time,appointment_slots.end_time 
		FROM patient_elig_avail,appointment_slots 
		WHERE 
		patient_elig_avail.day_of_the_week = appointment_slots.week_day 
		AND 
		appointment_slots.slot_date>= patient_elig_avail.eligible_date 
		AND 
		patient_elig_avail.start_time <= appointment_slots.start_time 
		AND 
		patient_elig_avail.end_time >= appointment_slots.end_time
		),
appointmentPreviouslyOffered AS(
SELECT DISTINCT appointment_matches.appointment_id,appointment_matches.patient_id
FROM 
appointment_matches
 JOIN
patient_slot_match
ON appointment_matches.appointment_id = patient_slot_match.appointment_id
WHERE
match_status = 'offered'
),
removeDclCnclMatches AS(
	SELECT patient_slot_match.appointment_id,patient_slot_match.patient_id
	FROM
	patient_slot_match
	EXCEPT
	(
		SELECT appointment_id,patient_id FROM 
		appointmentDclCnclPairs

	)
),
removePreviouslyOffered AS(
SELECT removeDclCnclMatches.appointment_id
	FROM
	removeDclCnclMatches
	EXCEPT
	(
		SELECT appointment_id
		FROM
		appointmentPreviouslyOffered
	)

),
removeAcceptCompletePatients AS(
SELECT patient_slot_match.patient_id
	FROM
	removePreviouslyOffered
	JOIN
	patient_slot_match
	ON
	removePreviouslyOffered.appointment_id = patient_slot_match.appointment_id
	EXCEPT
	(
	SELECT * FROM removePatientsAcceptedCompleted
	)
),
cleanSlotMatch AS(
	SELECT removeAcceptCompletePatients.patient_id,available_slots.appointment_id,available_slots.provider_id AS providerID,available_slots.slot_date,available_slots.start_time,available_slots.end_time
	FROM
	removeAcceptCompletePatients
	JOIN
	patient_slot_match
	ON 
	removeAcceptCompletePatients.patient_id = patient_slot_match.patient_id
	JOIN
	available_slots
	ON
	patient_slot_match.appointment_id = available_slots.appointment_id
)
INSERT INTO appointment_matches(appointment_id,patient_id,match_status,offerDateTime)
SELECT cleanSlotMatch.appointment_id,patients.patient_id,'offered',CURRENT_TIMESTAMP
FROM patients 
LEFT JOIN cleanSlotMatch 
ON patients.patient_id = cleanSlotMatch.patient_id 
LEFT JOIN max_distance 
ON max_distance.provider_id = cleanSlotMatch.providerID 
AND 
max_distance.patient_id = cleanSlotMatch.patient_id
WHERE
patients.group_id= @group
AND
max_distance.Distance<=patients.distance_preference
AND
cleanSlotMatch.appointment_id IS NOT NULL
ORDER BY 
max_distance.Distance ASC
--The below code is for testing purposes only
/*
EXEC AppointmentMatchingPrd @group = 1
EXEC AppointmentMatchingPrd @group = 2
EXEC AppointmentMatchingPrd @group = 3
EXEC AppointmentMatchingPrd @group = 4
EXEC AppointmentMatchingPrd @group = 5
EXEC AppointmentMatchingPrd @group = 6

select * from appointment_matches

select * from patients
DELETE FROM appointment_matches WHERE patient_id=8 AND match_status='offered'
EXEC AppointmentMatchingPrd @group = 1
SELECT *  
            FROM   sysobjects 
            WHERE  id = object_id(N'[dbo].[AppointmentMatching]') 
                   and OBJECTPROPERTY(id, N'IsProcedure') = 1
				   DROP PROCEDURE AppointmentMatchingPrd

				   */
				   