<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class createuser extends action{
	
	function action($data){
				
		$arr = array(
			'verify' => array(
				'fname' => $_POST['first_name'],
				'lname' => $_POST['last_name'],
				'uname' => $_POST['user_name'],
				'secret' => $_POST['secret'],
				'dept' => $_POST['dept'],
				'user_email' => $_POST['user_email'],
			)
		);
		
		$stmt = $data['pdo']->prepare("SELECT uname FROM {$this->dbprefix}users where uname =:uname");
		$stmt->execute(array('uname' => $_POST['user_name']));
		$row = $stmt->fetch();
		if(isset($row['uname'])){
			echo json_encode(array('error' => 'User All Ready Exist'));
			return false;
		}
		
		$stmt = $data['pdo']->prepare("insert into {$this->dbprefix}pusers values(:fname,:lname,:uname,:secret,:dept,:user_email)");
		$stmt->execute($arr['verify']);
		$link = $data['config']['purl'].'/login/verify/'.base64_encode($arr['verify']['secret'].serialize($arr));
		//SENDING MAIL TO NEW USER EMAIL
		if(file_exists('config/smtp.php')){
			$smtp_conf = include 'config/smtp.php';
			$mail = new PHPMailer;
			//$mail->SMTPDebug = 2;
			$mail->isSMTP();
			$mail->Host = $smtp_conf['host'];
			$mail->SMTPAuth = $smtp_conf['auth'];
			$mail->Username = $smtp_conf['email']; 
			$mail->Password = $smtp_conf['pass'];
			$mail->SMTPSecure = (($smtp_conf['secure'])? 'tls' : 'ssl');;
			$mail->Port = $smtp_conf['port'];  

			$mail->setFrom($smtp_conf['email'], $data['user']['fname'].' '.$data['user']['lname']);
			$mail->addAddress($arr['verify']['user_email'], $arr['verify']['fname'].' '.$arr['verify']['lname']);
			$mail->addReplyTo($arr['verify']['user_email'], 'Information');
			$mail->isHTML(true);
			$mail->Subject = 'Verify KChat user Link';
			$mail->Body    = 'Verify KChat user Link<br/><a href="'.$link.'" TARGET="_BLANK" >'.$link.'</a>';
			$mail->AltBody = 'Verify KChat user Link';
			
			if($mail->send()){
				$alert = 'Successfull Send';
			} else {
				$alert = $mail->ErrorInfo;
			}
		}else{
			$alert = 'Please Configure SMTP detail to send link';
		}
		
		//SENDING MAIL TO NEW USER EMAIL END
		
		echo json_encode(array('vlink' => base64_encode($arr['verify']['secret'].serialize($arr)),'alert' => $alert));
	}
}