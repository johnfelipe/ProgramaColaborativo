<?php
include_once "../lib/functions.php";

if(isset($_POST['propuesta']) && isset($_POST['usuario_id']))
{
	$propuesta = $_POST['propuesta'];
	$usuario   = $_POST['usuario_id'];
	
	try
	{
		$conn     = obtenerBBDD();
        $result   = $conn->prepare("SELECT * FROM prog_likes_enmiendas WHERE usuario_id=? AND enmienda_id IN (SELECT id from prog_enmiendas WHERE propuesta_id=?)");
        $result->bindParam(1, $usuario, PDO::PARAM_INT);
        $result->bindParam(2, $propuesta, PDO::PARAM_INT);
        $result->execute();

        //--
		$output = array();
		foreach($result as $res)
		{
			$output[] = array(
				'voto' => $res['voto'],
				'id' => $res['enmienda_id']
			);
		}
        
		//Array serializado para pasar datos con json.
		echo json_encode($output);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}
else
{	
	header('Location:index.php');
}

?>