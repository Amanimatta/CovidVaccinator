--CREATE DATABASE covidvaccinator;

CREATE TABLE priority_group (
    group_id           INTEGER,
    start_date              DATE,
    PRIMARY KEY (group_id)
);

CREATE TABLE patients (
    patient_id              INTEGER         IDENTITY(1,1)   NOT NULL 
    ,   first_name          VARCHAR(50)     NOT NULL
    ,   middle_initial      VARCHAR(1)      
    ,   last_name           VARCHAR(50)     
    ,   ssn                 VARCHAR(9)
    ,   dob                 DATE            NOT NULL
    ,   phone               VARCHAR(20)     NOT NULL
    ,   email               VARCHAR(320)    NOT NULL
    ,   street_number       VARCHAR(20)     NOT NULL
    ,   unit_number         VARCHAR(20)
    ,   street_name         VARCHAR(250)    NOT NULL
    ,   zip_code            VARCHAR(10)     NOT NULL
    ,   city                VARCHAR(200)    NOT NULL
    ,   [state]             VARCHAR(2)      NOT NULL  --2 letter state abbreviation
    ,   county              VARCHAR(100)    NOT NULL
    ,   latitude            DECIMAL(12,9)   
    ,   longitude           DECIMAL(12,9)   
    ,   [username]          VARCHAR(320)    NOT NULL UNIQUE 
    ,   pwd                 VARCHAR(64)     NOT NULL  
    ,   group_id            INTEGER         -- cannot but not null here.  It interferes with inserting records
    ,   distance_preference INTEGER         NOT NULL
    ,   PRIMARY KEY (patient_id)
    ,   FOREIGN KEY (group_id) REFERENCES priority_group ON UPDATE CASCADE
);


CREATE TABLE patient_documents (
    document_id             INTEGER         IDENTITY(1,1) NOT NULL
    ,   patient_id          INTEGER         NOT NULL
    ,   uploadDateTime      DATETIME        NOT NULL
    ,   document_name       VARCHAR(250)    NOT NULL
    ,   document            VARBINARY(MAX)    
    ,   PRIMARY KEY (document_id)
    ,   FOREIGN KEY (patient_id) REFERENCES patients ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE blocktime (
    blocktime_id            INTEGER         IDENTITY(1,1) NOT NULL
    ,  start_time           TIME            NOT NULL
    ,  end_time             TIME            NOT NULL
    PRIMARY KEY (blocktime_id)
);


CREATE TABLE patient_availability (
    patient_id              INTEGER         NOT NULL
    ,  day_of_the_week      VARCHAR(9)      NOT NULL 
    ,  blocktime_id         INTEGER         NOT NULL
    ,  PRIMARY KEY (patient_id, day_of_the_week, blocktime_id)
    ,  FOREIGN KEY (patient_id) REFERENCES patients ON DELETE CASCADE ON UPDATE CASCADE
    ,  FOREIGN KEY (blocktime_id) REFERENCES blocktime ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE providers (
    provider_id             INTEGER         IDENTITY(1,1) 
    ,   provider_type       VARCHAR(100)    NOT NULL
    ,   provider_name       VARCHAR(250)    NOT NULL
    ,   phone               VARCHAR(20)     NOT NULL
    ,   email               VARCHAR(320)    NOT NULL 
    ,   street_number       VARCHAR(20)     NOT NULL
    ,   unit_number         VARCHAR(20)
    ,   street_name         VARCHAR(250)    NOT NULL
    ,   zip_code            VARCHAR(10)     NOT NULL
    ,   city                VARCHAR(200)    NOT NULL
    ,   [state]             VARCHAR(2)      NOT NULL
    ,   county              VARCHAR(100)    NOT NULL
    ,   latitude            DECIMAL(12,9) 
    ,   longitude           DECIMAL(12,9)
    ,   [username]          VARCHAR(320)    NOT NULL UNIQUE 
    ,   pwd                 VARCHAR(64)     NOT NULL       
    ,   PRIMARY KEY (provider_id)
);

CREATE TABLE available_slots (
    appointment_id          INTEGER         IDENTITY(1,1) NOT NULL
    ,   provider_id         INTEGER         NOT NULL
    ,   slot_date           DATE            NOT NULL
    ,   start_time          TIME            NOT NULL
    ,   end_time            TIME            NOT NULL  
    ,   PRIMARY KEY (appointment_id)
    ,   FOREIGN KEY (provider_id) REFERENCES providers ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE appointment_matches (
    appointment_id          INTEGER         NOT NULL
    ,   patient_id          INTEGER         NOT NULL
    ,   match_status        VARCHAR(20)     NOT NULL --CHECK(match_status IN {'offered', 'accepted', 'declined', 'canceled', 'completed', 'missed'})
    ,   offerDateTime       DATETIME        NOT NULL
    ,   responseDateTime    DATETIME        --null if no response yet
    ,   providerNotified    DATETIME        --null if not notified yet   
    ,   PRIMARY KEY (appointment_id, patient_id)
    ,   FOREIGN KEY (appointment_id) REFERENCES available_slots ON DELETE CASCADE ON UPDATE CASCADE
    ,   FOREIGN KEY (patient_id) REFERENCES patients  ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE patient_preferred_contact (
    patient_id              INTEGER         NOT NULL
    , method                VARCHAR (20)    NOT NULL    --CHECK (method IN {'phone', 'e-mail', 'sms', 'mail'})
    , PRIMARY KEY (patient_id, method)
    , FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE provider_preferred_contact (
    provider_id              INTEGER        NOT NULL
    , method                 VARCHAR (20)   NOT NULL --CHECK (method IN {'phone', 'e-mail', 'sms', 'mail'})
    , PRIMARY KEY (provider_id, method)
    , FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE CASCADE ON UPDATE CASCADE
);



