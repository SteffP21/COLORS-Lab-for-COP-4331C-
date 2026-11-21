CREATE TABLE Users (
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Firstname VARCHAR(50) NOT NULL,
    Lastname VARCHAR(50) NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Passwordhash VARCHAR(50) NOT NULL
);

CREATE TABLE Colors (
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Colorname VARCHAR(50) NOT NULL,
    Userid INT NOT NULL
);

INSERT INTO Users (Id, Firstname, Lastname, Username, Passwordhash)
VALUES (1, 'testing', 'testing', 'testing', 'testing');

INSERT INTO Colors (Id, Colorname, Userid)
VALUES (1, 'yellow', 1);

INSERT INTO Colors (Id, Colorname, Userid)
VALUES (2, 'red', 1);

INSERT INTO Colors (Id, Colorname, Userid)
VALUES (3, 'purple', 1);