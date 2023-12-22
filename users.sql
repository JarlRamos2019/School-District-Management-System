set foreign_key_checks = 0;

drop table if exists SchoolRole;
create table SchoolRole (
    RoleName varchar(20) primary key
);

drop table if exists Users;
create table Users (
    Username varchar(15) primary key,
    Password varchar(60),
    Plaintext varchar(15),
    Role varchar(20),
    ID int unsigned
);

set foreign_key_checks = 1;
