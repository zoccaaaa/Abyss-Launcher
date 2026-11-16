# Abyss-Launcher

## Configuração local

O projeto usa o banco `projetofinal`. As credenciais locais antigas continuam como padrão (`localhost`, `root`/`root`); em outros ambientes, configure `ABYSS_DB_HOST`, `ABYSS_DB_NAME`, `ABYSS_DB_USER` e `ABYSS_DB_PASSWORD` no servidor PHP.

Antes de cadastrar contas com o fluxo atualizado, aplique a migração `database/migrations/001_hash_passwords.sql` no banco. Ela amplia a coluna de senha para comportar hashes. Contas antigas com senha em texto puro continuam podendo entrar; após um login bem-sucedido, a senha é convertida para hash.
