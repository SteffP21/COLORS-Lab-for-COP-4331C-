CREATE TABLE Users (
  ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  FirstName VARCHAR(50) NOT NULL,
  LastName VARCHAR(50) NOT NULL,
  Login VARCHAR(50) NOT NULL,
  Password VARCHAR(50) NOT NULL
);

CREATE TABLE Colors (
  ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(50) NOT NULL,
  UserID INT NOT NULL
);

INSERT INTO Users (ID, FirstName, LastName, Login, Password)
VALUES (1, 'testing', 'testing', 'testing', 'testing');

INSERT INTO Colors (ID, Name, UserID)
VALUES (1, 'yellow', 1);

INSERT INTO Colors (ID, Name, UserID)
VALUES (2, 'red', 1);

INSERT INTO Colors (ID, Name, UserID)
VALUES (3, 'purple', 1);