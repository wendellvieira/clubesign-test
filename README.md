# Seja bem vindo
Avaliação técnica para vaga dev fullstack clube-sign
## Sobre o teste
> O objeto deste teste é saber se o desenvolvedor consegue continuar um projeto já existente sem refatorar todo o projeto... será avaliado com maior pesso a capacidade de adptação do dev.
## Sobre a entrega 
> Clone este repositório e ao terminar envie o novo repositório para wendell.vieiracunha@gmail.com
## Instruções de instalação
- Dentro da pasta /desafio tem o arquivo chamado "clubedosign.sql" importe-o
- Mude a pasta do seu serviço http para a pasta "/wbs" 
- Altere os dados de ambiente dentro do arquivo "/wbs/configs/ambientes.config.php"
- Instale as dependencias do projeto "npm install"
- Rode o projeto "npm run dev"
- Acesse o painel Login: "admin", Senha: "abc123"
- Boa sorte
### Objetivos
> Os dois itens desse teste foram tirados de branchs recentes... então prepare-se porque vc está bem próximo do desafio em si.
- 1) Dentro do teste existe uma página de configuração em "/master/profile-settings"; Sua primeira missão é fazer com que essa página comece a salvar e ler as informações do perfil
- 2) Dentro da pasta existe uma imagem chamada "tela.png"; sua missão é criar aquela interface o mais fidedigna possível e isso é tudo.


## Uso do servidor
> Este projeto foi desenvolvido usando vue-cli + php nativo...

### Como disparar uma ação entre o cliente e o servidor
> O servidor foi desinvolvido usando a lógica de chamadas de ação. 
> Existe uma lib js que está encarregada da comunicação para chama-la basta importa-la conforme o exemplo abaixo..
```js
import WBS from 'js/http'
WBS.send("NOME-DA-ACAO", {DATA}, resp => {
    //código
})
```
> No lado do servidor o nome da ação é referencia ao armazenamento dos arquivos, então para a ação "public-custom-login" deve-se procurar os arquivos em "/wbs/actions/public/custom/login"
### Como criar uma ação
> Todas as ações são criadas dentro de uma pasta que contém o nome da ação; Dentro da pasta são necessários dois arquivos:
- /public/custom/login/action.php
```php
//O nome dessa função tem que ser o mesmo nome da pasta que deverá ser como será chamada a ação
// EX: essa função será acessada usando "public-custom-login"
function login($r){
    //Função que será chamada ao executar o chamado
}
```
- /public/custom/login/permission.php
```php
$permissions[] = array(
    "tables" => array("login", "sessions", "user_information")
    //Tabelas que serão utilizadas na ação
);
```
> Sempre que uma tabela é adiciona no hook "tables" ela é injetada dentro da variável $r['tables'] uma instância do class Query na função principal da ação.

### Uso da Class Query
> Recebida por injeção na função principal da ação essa classe é responsável por toda a manipulação das querys sql e comunicação do projeto..
> Seu uso é bem simples abaixo um exemplo de como fazer um crud.
```php
function addUser($r){
    return $r['tables']['login']->save($r['data'])->exec();
    // Aqui recebemos os dados vindos de $r['data'] depois passamos como argumento para o methodo save e executamos a função com o metodo exec.
}
function removeUser($r){
    return $r['tables']['login']->delete($r['data'])->exec();
}
function readUser($r){
    return $r['tables']['login']->read()->where("cod='{$r['data']['id']}'")->toArray();
}
```
> Em todos os casos acima o $r['data'] é o valor do segundo parametro passado pela função WBS.send no cliente.


