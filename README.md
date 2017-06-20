# Integração em PHP entre formulários próprios de sites com um formulário Mautic 

Crie o formulário no Mautic com os campos desejados (conforme exemplo acima). Após criar o formulário, faça as seguintes inserções no seu formulário em PHP já existente.

Insira essa biblioteca no seu formulário já existente
require_once("mautic.php");
Arquivo disponível no https://github.com/studiogt/mautic/archive/v1.0.zip

Aplique após o sucesso de seu formulário o seguinte código:
$form_data_array = array('email'=>$_POST['email'],'nome'=>$_POST['nome'],'fone'=>$_POST['telefone']);
pushMauticForm([endereco Mautic], $form_data_array, [id formulario]);

**Os campos em vermelho são os campos que você criou no Mautic.
** Nos espaços em verde, substitua pelos respectivos caminhos.

Exemplo: 

require_once("mautic.php");
$form_data_array = array('email'=>$_POST['email'],'nome'=>$_POST['nome'],'fone'=>$_POST['telefone']);
pushMauticForm('http://mautic.seudominio.com.br', $form_data_array, 3);
