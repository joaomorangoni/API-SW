# Como Utilizar?
- A API foi testada utilizando o *ThunderClient*
- Primeiro crie uma nova requisição e na URL coloque http://localhost/api-contatos/api.php
- no header coloque "Content-Type" e o valor "application/json"

# POST e PUT
- Em abas as requisições será necessário por os valores no body assim:
  {
  "nome" : "exemplo",
  "email" : "exemplo@email.com",
  "numero" : "959559595"
  }
- A diferença é que no PUT será necessário passar o id como parametro, basta colocar "?id=1" na URL

# GET e DELETE
- Basta passar o id como parametro da pra fazer tanto pelo query do thunderclient quanto pela URL colocando "?id=valor"
