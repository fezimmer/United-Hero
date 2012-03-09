CREATE TABLE tblUser(
pkUserID INT PRIMARY KEY AUTO_INCREMENT,
fldFName VARCHAR(30),
fldLName VARCHAR(40),
fldUsername VARCHAR(15),
fldPassword VARCHAR(25),
fldEmail VARCHAR(60),
fldPhone VARCHAR(13),
fldZip VARCHAR(5),
fldSignupDate DATETIME,
fldIPAddress VARCHAR(20),
fldType VARCHAR(15),
fldActive INT
);

CREATE TABLE tblUser_Activation(
pkUserID INT, 
fldCode VARCHAR(25)
);