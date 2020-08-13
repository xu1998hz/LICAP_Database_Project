ALTER TABLE LAMINATOR ADD (LAM_DATE_2 DATE);
-- Update the column value in LAM_DATE
UPDATE LAMINATOR SET LAM_DATE = REPLACE(LAM_DATE, '/', '-') WHERE 1;
-- Convert the data type to date data type in the new column
UPDATE LAMINATOR SET LAM_DATE_2=STR_TO_DATE(LAM_DATE, '%m-%d-%Y');
-- DROP the old column
ALTER TABLE LAMINATOR DROP LAM_DATE;
-- rename the column name back
ALTER TABLE `LAMINATOR` CHANGE `LAM_DATE_2` `LAM_DATE` DATE NULL DEFAULT NULL;

ALTER TABLE FILM ADD (DATE_2 DATE);
-- Update the column value in LAM_DATE
UPDATE FILM SET DATE = REPLACE(DATE, '/', '-') WHERE 1;
-- Convert the data type to date data type in the new column
UPDATE FILM SET DATE_2=STR_TO_DATE(DATE, '%m-%d-%Y');
-- DROP the old column
ALTER TABLE FILM DROP DATE;
-- rename the column name back
ALTER TABLE `FILM` CHANGE `DATE_2` `DATE` DATE NULL DEFAULT NULL;
