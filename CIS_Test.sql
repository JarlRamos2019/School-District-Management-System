set foreign_key_checks = 0;
drop table if exists ClassInSchool;

create table ClassInSchool (
    TeacherID2 int unsigned not null primary key
);
set foreign_key_checks = 1;
