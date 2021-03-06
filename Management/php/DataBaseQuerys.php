  <?php
include_once('../php/class/Database.php');
//String of database connection  
$connection = Database::getConnection();

/**
 * Load Client table
 */
function loadLink($sql, $pagename, $limit)
{
	global $connection;
	$result            = mysqli_query($connection, $sql);
	$number_of_results = mysqli_num_rows($result);
	
	echo "<div class='pagination'>";
	for ($page = 1; $page <= round($number_of_results / $limit); $page++)
	{
		echo "<a href='$pagename.php?pg=$page&lmt=$limit'>$page</a>";
	}
	echo "</div>";
}

function tratarPaginaELimiteDeEmForms()
{
	if (isset($_POST["btnsearch"]))
	{
        if ((isset($_POST["ans-start"]) && isset($_POST["ans-end"])) && ($_POST["ans-start"] != "" 
        && $_POST["ans-end"] != "") && ((isset($_POST["send-start"]) && isset($_POST["send-end"])) 
        && ($_POST["send-start"] != "" && $_POST["send-end"] != "")))
		{
            loadFormByAnswerAndSendAsFilter($_GET["pg"], $_GET["lmt"], $_POST["send-start"],
             $_POST["send-end"], $_POST["ans-start"], $_POST["send-end"]);
		}
		
        else if ((isset($_POST["send-start"]) && isset($_POST["send-end"])) && ($_POST["send-start"] != ""
         && $_POST["send-end"] != ""))
		{
            loadFormsBySendDateAsFilter($_GET["pg"], $_GET["lmt"], $_POST["send-start"],
             $_POST["send-end"]);
		}
		
        else if ((isset($_POST["ans-start"]) && isset($_POST["ans-end"])) && $_POST["ans-start"] != "" 
        && $_POST["ans-end"] != "")
		{
            loadFormsByAnswerDateAsFilter($_GET["pg"], $_GET["lmt"], $_POST["ans-start"], 
            $_POST["ans-end"]);
		}
		else
		{
			loadForms($_GET["pg"], $_GET["lmt"]);
		}
	}
	else
	{
		loadForms($_GET["pg"], $_GET["lmt"]);
	}
}

/**
 * Load informations of client table
 */
function loadClient($page, $limit)
{
	global $connection;
	
	$startResult = ($page - 1) * $limit;
	$result      = $connection->query("SELECT * FROM customer ORDER BY name asc limit $startResult,$limit");
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			
            echo "<tr><td><a class='linkname' href='../pages/mainprofile.php?profile=" . $row["idcustomer"] 
            . "&type=c'>" . utf8_encode($row["name"]) . "</a></td>";
			echo "<td>" . $row["tecnical_visits"] . "</td>";
			echo "<td>" . $row["forms_answereds"] . "</td>";
			echo "<td>" . $row["avaliation_avarage"] . "</td>";
			echo "<td>" . $row["effectiviness"] . "%</td>";
		}
	}
}
/**
 * retorna a string formatada para ser gerado o html
 */
function formatarComentario($commentary)
{
	if ($commentary == null || $commentary == "")
	{
		return "<td>-</td>";
	}
	else
	{
        return "<td data-toggle='tooltip' title='" . utf8_encode($commentary) . "'>" . 
        utf8_encode($commentary) . "</td>";
	}
}
/**
 * Trata a string referente a nota
 */
function tratarNotaDeAvaliacao($nota)
{
	if ($nota == 5)
		return "<td title = '5 - Excelente'>5 - Excelente</td>";
	else if ($nota == 4)
		return "<td title = '4 - Muito bom'>4 - Muito bom</td>";
	else if ($nota == 3)
		return "<td title = '3 - Bom'>3 - Bom</td>";
	else if ($nota == 2)
		return "<td title = '2 - Regular'>2 - Regular</td>";
	return "<td title = '1 - Ruim'>1 - Ruim</td>";
}
/**
 * Formata a string referente a tabela 'solução dos problemas'
 */
function tratarSolucaoDoProblema($issue_solved)
{
	if ($issue_solved == "yes")
	{
		return "Sim";
	}
	return "Não";
}
/**
 * Load informations of employeers table
 */
function loadEmployers($page)
{
	global $connection;
	
	$startResult = ($page - 1) * 25;
	$result      = $connection->query("SELECT * FROM employee ORDER BY idemployee asc limit $startResult,25");
	
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
            echo "<tr><td title='" . utf8_encode($row["name"]) . "' ><a class='linkname' href='../pages/mainprofile.php?profile="
             . $row["idemployee"] . "&type=e'>" . utf8_encode($row["name"]) . "</a></td>";
			echo "<td>" . $row["note_avarage"] . "</td>";
			echo "<td>" . $row["visits"] . "</td>";
		}
	}
}
/**
 * Formata a string de data para o formato pt-br
 */
function formatDate($string)
{
	return date("d/m/Y", strtotime($string));
}
function formatDateForQuery($string)
{
	return str_replace("/", "-", $string);
}
/**
 * Carrega a tabela de formulário possuindo a data de envio do formulário como filtro
 */
function loadFormsByAnswerDateAsFilter($page, $limit, $date_start, $date_end)
{
	
	$startResult = ($page - 1) * $limit;
	$queryString = "SELECT customer.idcustomer, employee.idemployee, customer.name as customer_name, employee.name as employee_name,
    form.evaluation_value as val, form.issue_solve as solved, form.commentary as comment, form.request_sent
     as sent_date, form.idform, form.request_answered as answered_date from ((form inner join customer on form.idcustomer 
     = customer.V11_ID)inner join employee on form.idemployee = employee.V11_code) where form.request_answered between '"
      . formatDateForQuery($date_start) . "' and '" . formatDateForQuery($date_end) . "' ORDER BY customer.name asc limit $startResult,$limit";
	
	formRunqueryAndDisplay($queryString);
}
function loadFormByAnswerAndSendAsFilter($page, $limit, $date_sent_start, $date_sent_end, $date_ans_start,
 $date_ans_end)
{	
	$startResult = ($page - 1) * $limit;
	$queryString = "SELECT customer.idcustomer, employee.idemployee, customer.name as customer_name, employee.name as employee_name,
    form.evaluation_value as val, form.issue_solve as solved, form.commentary as comment, form.request_sent
     as sent_date, form.idform, form.request_answered as answered_date from ((form inner join customer on form.idcustomer 
     = customer.V11_ID)inner join employee on form.idemployee = employee.V11_code) where form.request_answered between '"
      . formatDateForQuery($date_ans_start) . "' and '" . formatDateForQuery($date_ans_end) . "' and form.request_sent between '" . formatDateForQuery($date_sent_start) . "' and '" . formatDateForQuery($date_sent_end) . "'  ORDER BY customer.name asc limit $startResult,$limit";
	
	formRunqueryAndDisplay($queryString);
}
/**
 * Carrega a tabela de formulário possuindo a data de envio do formulário como filtro
 */
function loadFormsBySendDateAsFilter($page, $limit, $date_start, $date_end)
{
	$startResult = ($page - 1) * $limit;
	$queryString = "SELECT customer.idcustomer, employee.idemployee, customer.name as customer_name, employee.name as employee_name,
    form.evaluation_value as val, form.idform, form.issue_solve as solved, form.commentary as comment, form.request_sent
     as sent_date, form.request_answered as answered_date from ((form inner join customer on form.idcustomer 
     = customer.V11_ID)inner join employee on form.idemployee = employee.V11_code) where form.request_sent between '" . formatDateForQuery($date_start) . "' and '" . formatDateForQuery($date_end) . "' ORDER BY customer.name asc limit $startResult,$limit";
	
	formRunqueryAndDisplay($queryString);
}
/**
 * Exibe o resultado proveniente da query de pesquisa na tabela Forms
 */
function formRunqueryAndDisplay($queryString)
{
	global $connection;
	
	$result = $connection->query($queryString);
	
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			echo "<tr><td title='Visualizar formulário completo'><a href='../pages/completeform.php?id=".$row["idform"]."'><img src='../img/info.png' alt='Visualizar formulário'></a></td>";
            echo "<td title='" . utf8_encode($row["customer_name"]) . "'><a class='linkname' href='../pages/mainprofile.php?profile="
             . $row["idcustomer"] . "&type=c'>" . utf8_encode($row["customer_name"]) . "</a></td>";
            echo "<td td tite='" . utf8_encode($row["employee_name"]) . "'><a class='linkname' href='../pages/mainprofile.php?profile=" 
            . $row["idemployee"] . "&type=e'>" . utf8_encode($row["employee_name"]) . "</a></td>";
			echo tratarNotaDeAvaliacao($row["val"]);
			echo "<td>" . tratarSolucaoDoProblema($row["solved"]) . "</td>";
			echo formatarComentario($row["comment"]);
			echo "<td>" . formatDate($row["sent_date"]) . "</td>";
			echo "<td>" . formatDate($row["answered_date"]) . "</td>";
		}
	}
}
/**
 * Carrega as informações da tabela form na pagina HTML
 */
function loadForms($page, $limit)
{	
	$startResult = ($page - 1) * $limit;
	$queryString = "SELECT customer.idcustomer, employee.idemployee, customer.name as customer_name, employee.name as employee_name,
    form.evaluation_value as val, form.issue_solve as solved, form.commentary as comment, form.request_sent
     as sent_date, form.idform, form.request_answered as answered_date from ((form inner join customer on form.idcustomer 
     = customer.V11_ID)inner join employee on form.idemployee = employee.V11_code) ORDER BY customer.name asc limit
      $startResult,$limit";
	
	formRunqueryAndDisplay($queryString);
}
/**
 * Check in mainprofile's GET where type is referencing
 */
function GetTableReference($type)
{
	if ($type == "c")
		return "customer";
	else
		return "employee";
}

function LoadCustomerProfile($id, $name)
{
	global $connection;
	
	$result = $connection->query("SELECT * FROM form WHERE id$name = $id");
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			$get  = $connection->query("SELECT name from $name where id$name = $id");
			$name = $get->fetch_assoc();
			echo "<td>" . utf8_encode($name["name"]) . "</td>";
		}
	}
}
/**
 * Retorna as informações referente a uma tabela com um íd
 */
function LoadDataFrom($id, $table)
{
	global $connection;
	
	$result = $connection->query("SELECT * FROM $table WHERE id$table = $id");
	if ($result->num_rows > 0){
		return $result->fetch_assoc();
	}
	else{
		return null;
	}
}
/**
 * Retorna as informações refente a uma tabela usando o código VIP para pesquisa
 */
function LoadDataFromVIP($id, $table){
	global $connection;
	
	if($table == "employee"){
		$result = $connection->query("SELECT * FROM $table WHERE V11_code = $id");
	}
	else{
		$result = $connection->query("SELECT * FROM $table WHERE V11_ID = $id");
	}
	$output = $result->fetch_assoc();
	
	if ($result->num_rows > 0)
	{
		return $output;
	}
	else
	{
		return null;
	}
}
/**
 * Load table referenced to profile historic
 */
function LoadTableColuns($kind)
{
	if ($kind == "c")
	{
		echo "<th>Nome do técnico</th>";
	}
	else if ($kind == "e")
	{
		echo "<th>Nome do cliente</th>";
	}
	else
	{
		echo "";
	}
	echo "<th id='nota'>Nota</th>";
	echo "<th>Problema resolvido ?</th>";
	echo "<th>Comentário</th>";
	echo "<th>Data de envio da pesquisa</th>";
	echo "<th>Data de resposta da pesquisa</th>";
}
/**
 * carrega o número de registros das tabelas
 */
function GetNumberFromQuery($sql1)
{
	$sql = $sql1;
	global $connection;
	$result = $connection->query($sql);
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		foreach ($row as $val)
		{
			return $val;
		}
	}
	else
	{
		return 0;
	}
}
/**
 * carrega o nome de um usuario através do seu ID
 */
function GetNameFromBD($id, $table)
{
	global $connection;
	$get  = $connection->query("SELECT name FROM $table WHERE id$table = $id");
	$name = $get->fetch_assoc();
	return utf8_encode($name["name"]);
}
/**
 * Carrega os emails registrados de um cliente
 */
function GetEmailsFromBD($id, $table)
{
	global $connection;
	$get           = $connection->query("SELECT emails FROM $table WHERE id$table = $id");
	$name          = $get->fetch_assoc();
	$val_to_return = "";
	foreach ($name as $single_mail)
	{
		return $single_mail;
	}
}
/** 
 * Carrega o historico de um cliente ou funcionário
 */
function LoadHistoric($from, $id_vip)
{
	global $connection;
	
	$identity = "id" . $from;
    $result   = $connection->query("SELECT customer.idcustomer, employee.idemployee, evaluation_value,".
    "issue_solve,commentary, request_sent, request_answered from ((form
    inner join customer on form.idcustomer = customer.V11_ID)
    inner join employee on form.idemployee = employee.V11_code)
    where form." . $identity . "= $id_vip order by idform desc");
	
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if ($from == "employee")
			{
                $result2 = $connection->query("select name from customer where idcustomer =" . 
                $row["idcustomer"]);
			}
			else
			{
                $result2 = $connection->query("select name from employee where idemployee =" .
                 $row["idemployee"]);
			}
			$row2 = $result2->fetch_assoc();
			
			if ($from == "employee")
			{
                echo "<tr><td><a class='linkname' href='../pages/mainprofile.php?profile=" . 
                $row["idcustomer"] . "&type=c'>" . utf8_encode($row2["name"]) . "</a></td>";
			}
			else
			{
                echo "<tr><td><a class='linkname' href='../pages/mainprofile.php?profile=" .
                 $row["idemployee"] . "&type=e'>" . utf8_encode($row2["name"]) . "</a></td>";
			}
			
			echo tratarNotaDeAvaliacao($row["evaluation_value"]);
			echo "<td>" . tratarSolucaoDoProblema($row["issue_solve"]) . "</td>";
			echo formatarComentario($row["commentary"]);
			echo "<td>" . $row["request_sent"] . "</td>";
			echo "<td>" . $row["request_answered"] . "</td></tr>";
		}
    }
}

/**
 * Altera a senha do usuário
 *
 * @param [type] $newpass
 * @param [type] $passconfirm
 * @param [type] $userid
 * @return void
 */
function changepwd($newpass, $passconfirm, $userid){
	global $connection;
	if($newpass == $passconfirm)
	{
		if($connection->query("UPDATE users set password = '".$newpass. "' where idusers = ".$userid) === true){
			return "<div class='alert alert-success' role='alert'>
  					Senha alterada com sucesso
				</div>";
		}
		return "<div class='alert alert-danger' role='alert'>
					Não foi  possível alterar a senha;
  				</div>";
	}
	else{
		return "<div class='alert alert-warning' role='alert'>
					As senhas não são iguais.
	  			</div>";
	}
}
?>