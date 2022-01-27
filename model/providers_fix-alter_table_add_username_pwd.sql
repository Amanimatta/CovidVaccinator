ALTER TABLE providers ADD [username] VARCHAR (320);
ALTER TABLE providers ADD pwd VARCHAR (64);


UPDATE providers SET [username] = 'user1', pwd = 'password1' WHERE provider_id = 1;
UPDATE providers SET [username] = 'user2', pwd = 'password2' WHERE provider_id = 2;
UPDATE providers SET [username] = 'user3', pwd = 'password3' WHERE provider_id = 3;
UPDATE providers SET [username] = 'user4', pwd = 'password4' WHERE provider_id = 4;
UPDATE providers SET [username] = 'user5', pwd = 'password5' WHERE provider_id = 5;
UPDATE providers SET [username] = 'user6', pwd = 'password6' WHERE provider_id = 6;


select * from providers;
