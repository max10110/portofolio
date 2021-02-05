<?php
if ($_POST['Имя']){$name=$_POST['Имя'];}
if ($_POST['Телефон']){$phone=$_POST['Телефон'];}
$comm=array();
if ($_POST['Габариты_шкафа']){$comm[]="Габариты шкафа: ".$_POST['Габариты_шкафа'];}
if ($_POST['Ширина']){$comm[]="Ширина: ".$_POST['Ширина'];}
if ($_POST['Высота']){$comm[]="Высота: ".$_POST['Высота'];}
if ($_POST['Глубина']){$comm[]="Глубина: ".$_POST['Глубина'];}
if ($_POST['Количество_дверей']){$comm[]="Количество дверей: ".$_POST['Количество_дверей'];}
if ($_POST['Фасад']){$comm[]="Фасад: ".$_POST['Фасад'];}
if ($_POST['Ссылка_на_картинку']){$comm[]="Ссылка на картинку: ".$_POST['Ссылка_на_картинку'];}

$comments=implode("\r\n",$comm);

$user=array(
	'USER_LOGIN'=>'christinachka123@gmail.com', #Ваш логин (электронная почта)
	'USER_HASH'=>'645c8e7f978b9e1de07d14d7dc00c89367a1a53f' #Хэш для доступа к API (смотрите в профиле пользователя)
);

$subdomain='thebestwindows';
$link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_POST,true);curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);$out=curl_exec($curl);$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);curl_close($curl);



$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current';
$curl=curl_init(); curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); curl_setopt($curl,CURLOPT_URL,$link); curl_setopt($curl,CURLOPT_HEADER,false); curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); $out=curl_exec($curl); curl_close($curl);

$Response=json_decode($out,true);
$account=$Response['response']['account'];
foreach($account['custom_fields']['contacts'] as $key =>$value){
	if ($value['code']=="PHONE"){$phone_id=$value['id'];}
	if ($value['code']=="EMAIL"){$email_id=$value['id'];}
}


$yes_search="0";
$last_leads=array();
$last_lead='';

if (isset($phone) and $phone!=''){
		$phone2=$phone;
		if ($phone2[0]==8){$phone2[0]="7";}
		$phone2=str_replace("+7", "7",$phone2);$phone2=str_replace("(", "",$phone);$phone2=str_replace(")", "",$phone2);$phone2=str_replace("+", "",$phone2);$phone2=str_replace(" ", "",$phone2);$phone2=str_replace("-", "",$phone2);	 		
		
		$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list?query='.$phone2.'&limit_rows=1';
		$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);$out=curl_exec($curl);$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);curl_close($curl);
		$out_phone=(json_decode($out));
		
	
		if ($out_phone){
			$custom_fields=($out_phone->response->contacts[0]->custom_fields);	
			foreach($custom_fields as $key=>$custom_field){
				if ($custom_field->id==$phone_id){
					$phone_amo=$custom_field->values[0]->value;
					$phone_amo=str_replace("+7", "7",$phone_amo); $phone_amo=str_replace("(", "",$phone_amo); $phone_amo=str_replace(")", "",$phone_amo); $phone_amo=str_replace("+", "",$phone_amo); $phone_amo=str_replace(" ", "",$phone_amo); $phone_amo=str_replace("-", "",$phone_amo);
					if ($phone2==$phone_amo){
						$yes_search="1";
						$contact_id=$out_phone->response->contacts[0]->id; //!+
						$leads_arr=$out_phone->response->contacts[0]->linked_leads_id; //!++
						
						$linked_company_id=$out_phone->response->contacts[0]->linked_company_id; //!++
						
						if ($leads_arr){
							foreach($leads_arr as $lead_search){
								$search_leads_arr=array();
								foreach($leads_arr as $leads_arr_id){$search_leads_arr[]='id[]='.$leads_arr_id;}
								$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads?'.implode("&",$search_leads_arr);
								$curl=curl_init(); curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); curl_setopt($curl,CURLOPT_URL,$link); curl_setopt($curl,CURLOPT_HEADER,false); curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); $out=curl_exec($curl); curl_close($curl);
								$response=json_decode($out,TRUE);

								$last_leads_not142=array();
								foreach($response['response']['leads'] as $value){
									if ($value['status_id']!=142 and $value['status_id']!=143){
										$last_leads_not142[]=$value['id'];
									}
								}
								if ($last_leads_not142){
									$last_lead=max($last_leads_not142); // !++
								}
							}
						}	
					}
				}
			}
		}
}

if (isset($email) and $email!='' and $yes_search==0){
		$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list?query='.$email.'&limit_rows=1';
		$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);$out=curl_exec($curl);$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);curl_close($curl);
		$out_email=(json_decode($out));
		if ($out_email){
			$custom_fields=($out_email->response->contacts[0]->custom_fields);	
			foreach($custom_fields as $key=>$custom_field){
				if ($custom_field->id==$email_id){
					if ($email==$custom_field->values[0]->value){
						$yes_search="1";
						$contact_id=$out_email->response->contacts[0]->id; //!+
						$leads_arr=$out_email->response->contacts[0]->linked_leads_id; //!++
						if ($leads_arr){
							foreach($leads_arr as $lead_search){
								$search_leads_arr=array();
								foreach($leads_arr as $leads_arr_id){$search_leads_arr[]='id[]='.$leads_arr_id;}
								$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads?'.implode("&",$search_leads_arr);
								$curl=curl_init(); curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); curl_setopt($curl,CURLOPT_URL,$link); curl_setopt($curl,CURLOPT_HEADER,false); curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); $out=curl_exec($curl); curl_close($curl);
								$response=json_decode($out,TRUE);
								$last_leads_not142=array();
								foreach($response['response']['leads'] as $value){
									if ($value['status_id']!=142 and $value['status_id']!=143){
										$last_leads_not142[]=$value['id'];
									}
								}
								if ($last_leads_not142){
									$last_lead=max($last_leads_not142); // !++
								}
							}
						}	
					}
				}
			}
		}
}


$responsible_user_id=858375;
$ip=$_SERVER['REMOTE_ADDR'];

$sdelka=array(
	'name'=>"Заявка с сайта (Мебель)",
	'status_id'=>24506473,
	'price'=>$summ,
	'responsible_user_id'=>$responsible_user_id,
	'tags'=>"mebel"
);



$expectMarks = array('utm_source','utm_medium','utm_campaign','utm_term','utm_content');$utms=array();foreach($expectMarks as $utm){if(isset($_COOKIE[$utm])){${$utm}=$_COOKIE[$utm];}}
if ($utm_source){$sdelka['custom_fields'][]=array('id'=>404005,'values'=>array(array('value'=>$utm_source)));}
if ($utm_medium){$sdelka['custom_fields'][]=array('id'=>404033,'values'=>array(array('value'=>$utm_medium)));}
if ($utm_campaign){$sdelka['custom_fields'][]=array('id'=>404007,'values'=>array(array('value'=>$utm_campaign)));}
if ($utm_term){$sdelka['custom_fields'][]=array('id'=>404027,'values'=>array(array('value'=>$utm_term)));}
if ($utm_content){$sdelka['custom_fields'][]=array('id'=>404025,'values'=>array(array('value'=>$utm_content)));}



$leads['request']['leads']['add'][]=$sdelka;
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);$out=curl_exec($curl);
$Response=json_decode($out,true);
$idsdelka_new=$Response['response']['leads']['add'][0]['id'];

if (isset($comments)){
	$notes['request']['notes']['add']=array(
	array(
		'element_id'=>$idsdelka_new,
		'element_type'=>2,
		'note_type'=>4,
		'text'=>$comments,
		'responsible_user_id'=>$responsible_user_id,
	));
	$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/notes/set';
	$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($notes));curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);$out4=curl_exec($curl);$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);curl_close($curl);
}
	
	
if ($yes_search==1 and $contact_id){
	$leads_arr[]=$idsdelka_new;
	$seyhas=time();
	$contact=array(
	  'id'=>$contact_id,
	  'linked_leads_id'=>$leads_arr,
	  'last_modified'=>$seyhas
	);
	$set['request']['contacts']['update'][]=$contact;
	$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
	$curl=curl_init(); curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); curl_setopt($curl,CURLOPT_URL,$link); curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST'); curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set)); curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json')); curl_setopt($curl,CURLOPT_HEADER,false); curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); $out=curl_exec($curl); 


	if ($linked_company_id>0){
		$company_arr=array(
		  'id'=>$linked_company_id,
		  'linked_leads_id'=>$leads_arr,
		  'updated_at'=>$seyhas
		);
		$set['update']=array($company_arr);
		$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/companies';
		$curl=curl_init(); curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); curl_setopt($curl,CURLOPT_URL,$link); curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST'); curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set)); curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json')); curl_setopt($curl,CURLOPT_HEADER,false); curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); 
		$out=curl_exec($curl); 
	}
	
} else {
	if ($name==''){$name=$phone;}
	$contact=array(
	  'name'=>$name,
	  'linked_leads_id'=>array($idsdelka_new),
	  'responsible_user_id'=>$responsible_user_id
	);
	if ($phone){$contact['custom_fields'][]=array('id'=>$phone_id,'values'=>array(array('value'=>$phone,'enum'=>'MOB')));}
	if ($email){$contact['custom_fields'][]=array('id'=>$email_id,'values'=>array(array('value'=>$email,'enum'=>'WORK')));}
	
	
	
	
	$expectMarks = array('utm_source','utm_medium','utm_campaign','utm_term','utm_content');$utms=array();foreach($expectMarks as $utm){if(isset($_COOKIE[$utm])){${$utm}=$_COOKIE[$utm];}}
	if ($utm_source){$contact['custom_fields'][]=array('id'=>528935,'values'=>array(array('value'=>$utm_source)));}
	if ($utm_medium){$contact['custom_fields'][]=array('id'=>528927,'values'=>array(array('value'=>$utm_medium)));}
	if ($utm_campaign){$contact['custom_fields'][]=array('id'=>528929,'values'=>array(array('value'=>$utm_campaign)));}
	if ($utm_term){$contact['custom_fields'][]=array('id'=>528933,'values'=>array(array('value'=>$utm_term)));}
	if ($utm_content){$contact['custom_fields'][]=array('id'=>528931,'values'=>array(array('value'=>$utm_content)));}

	
	
	
	
	$set['request']['contacts']['add'][]=$contact;
	$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
	$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	$out=curl_exec($curl);
	$Response=json_decode($out,true);
	
	$id_contact=$Response['response']['contacts']['add'][0]['id'];
	
	
	
	
	if ($company){
		$company_arr=array(
			'name' => $company,
			'contacts_id' => array($id_contact),
			'leads_id' => array($idsdelka_new),
		);
		if ($phone){$company_arr['custom_fields'][]=array('id'=>$phone_id,'values'=>array(array('value'=>$phone,'enum'=>'MOB')));}
		if ($email){$company_arr['custom_fields'][]=array('id'=>$email_id,'values'=>array(array('value'=>$email,'enum'=>'WORK')));}
		
		$set['add'] = array($company_arr);
		$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/companies';
		$curl=curl_init();curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');curl_setopt($curl,CURLOPT_URL,$link);curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));curl_setopt($curl,CURLOPT_HEADER,false);curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		$out=curl_exec($curl);
		
	}
	
	
	
	
}

?>