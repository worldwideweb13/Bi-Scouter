INSERT INTO gs_an_table
(name,email,naiyou,indate)
VALUES
('SUZUKI','test02@test','テスト送信です。',sysdate())


INSERT INTO gs_an_table(name,email,naiyou,indate)VALUES(:name,:email,:naiyou,sysdate())




SELECT * FROM gs_an_table
SELECT name,email FROM gs_an_table
SELECT * FROM gs_an_table WHERE email LIKE '%test%'
SELECT * FROM gs_an_table ORDER BY indate DESC LIMIT 3