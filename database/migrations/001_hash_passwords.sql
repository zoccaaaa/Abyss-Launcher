-- password_hash(PASSWORD_DEFAULT) precisa de espaço para hashes atuais e futuros.
ALTER TABLE usuarios MODIFY COLUMN senha VARCHAR(255) NOT NULL;
