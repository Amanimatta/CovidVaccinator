BULK INSERT dbo.priority_group
FROM 'H:\data\priority_group.csv'
With(
FIRSTROW=2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

BULK INSERT dbo.patients
FROM 'H:\data\patients.csv'
With(
FIRSTROW=1,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

BULK INSERT dbo.blocktime
FROM 'H:\data\blocktime.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

BULK INSERT dbo.providers
FROM '\tmp\csv2\providers.csv'
With(
FIRSTROW=1,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)


BULK INSERT dbo.available_slots
FROM 'H:\data\available_slots.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

BULK INSERT dbo.patient_availability
FROM 'H:\data\patient_availability.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

BULK INSERT dbo.appointment_matches
FROM 'H:\data\appointment_matches.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)


BULK INSERT dbo.patient_preferred_contact
FROM 'H:\data\patient_preferred_contact.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)
BULK INSERT dbo.provider_preferred_contact
FROM 'H:\data\provider_preferred_contact.csv'
With(
FIRSTROW = 2,
    FIELDTERMINATOR = ',',  --CSV field delimiter
    ROWTERMINATOR = '\n',   --Use to shift the control to next row
    TABLOCK
)

