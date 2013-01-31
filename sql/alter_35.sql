CREATE TABLE user_session
(
    id CHAR(32) PRIMARY KEY,
    expire INTEGER,
    data TEXT
)