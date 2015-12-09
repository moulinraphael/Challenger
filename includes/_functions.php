<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_functions.php *********************************/
/* Plusieurs fonctions très utilisées **********************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/



//Fonction relative à l'état de l'interface
function isCLI() {
	return in_array(php_sapi_name(), array('cli', 'cli-server'));
}


//Fonctions relatives aux chaines de caractères et aux contenus
function onlyLetters($string) {
	return preg_replace('/[^\p{L}\p{N} \'.-]+/', '', $string);
}

function secure($string) {
	return trim(htmlspecialchars(addslashes($string)));
}

function printDate($s, $sentence = false) {
	$t = time();
	if (empty($s)) return '';
	else if ($t - $s < 60) return $sentence ? 'À l\'instant' : 'maintenant';
	else if($t - $s < 3600 && date('d') == date('d', $s)) return ($sentence ? 'Il y a ' : null).(int) (($t - $s) / 60).'min';
	else if($t - $s < 86400 && date('d') == date('d', $s)) return ($sentence ? 'Il y a ' : null).(int) (($t - $s) / 3600).'h';
	else if($t - $s < 86400) return 'Hier à '.date('H:i', $s);
	else return ($sentence ? 'Le ' : 'le ').date('d\/m\/y à H:i', $s);
}

function printDateTime($date, $sentence = true) {
	$date = new DateTime($date);
	return printDate($date->getTimestamp(), $sentence);
}

function url($route = '', $abs = false, $echo = true) {
	$secure = $_SERVER['SERVER_PORT'] == 443;
	$server = $_SERVER['SERVER_NAME'];
	$dir = DIR_APP.'/';
	$return = ($abs ? 'http'.($secure ? 's' : '').'://'.$server : '').$dir.$route;
	if (!$echo)
		return $return;
	echo $return;
}

function hashPass($string = '') {
	$seed = !defined('APP_SEED') || empty(APP_SEED) ? '' : APP_SEED;
	return hash('sha256', $string.$seed);
}

function printMoney($money = 0, $devise = '€') {
	return sprintf('%.2f '.$devise, $money);
}

function isValidEmail($email_address) {
	//Norme RFC 5322
    return preg_match('/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)'.
		'((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)'.
		'(\((?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)'.
		'([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*(?2)")'.
		'(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z\d-]{64,})(?1)(?>([a-z\d](?>[a-z\d-]*[a-z\d])?)'.
		'(?>(?1)\.(?!(?1)[a-z\d-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f\d]{1,4})'.
		'(?>:(?6)){7}|(?!(?:.*[a-f\d][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|'.
		'(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f\d]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?'.
		'(25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)(?>\.(?9)){3}))\])(?1)$/isD', $email_address);
}


//Fonctions pour le filtrage des fiches
//On découpe en groupes séparés par des guillemets
//Les groupes non entourés par les " sont découpés en mots
//La recherche peut être limitée à une recherche stricte,
//dès qu'un élement du filtrage est trouvé on le supprime de la chaine étudiée
function filter_empty($str) {
	return $str != '';
}

function make_filtres($filtre) {

	//Découpe du filtre en sous-filtres
	$filtre = strtolower(preg_replace('/\s+/', ' ', secure($filtre)));
	$groupes = explode(secure('"'), $filtre);
	$filtres = array();
	
	foreach ($groupes as $i => $groupe) {
		$groupe = trim($groupe);

		//Si le groupe n'est pas entre guillemets ou si c'est le dernier groupe
		//On découpe alors le groupe à l'aide des espaces 
		if (!($i % 2) ||
			end($groupes) == $groupe)
			$filtres = array_merge($filtres, explode(' ', $groupe));


		//Sinon on conserve le groupe en entier
		else
			$filtres[] = $groupe;
	}

	//On supprime les filtres vides
	return array_filter($filtres, 'filter_empty');
}

function search_filtres($haystack, $filtres, $limitsearch = false) {
	$print = true;

	//Pour chacun des filtres...
	foreach ($filtres as $filtre) {

		//Si le filtre est non vide et qu'il n'est pas trouvé, alors on ne doit pas afficher l'élément concerné
		if ($filtre != '' &&
			strpos($haystack, $filtre) === false) {
			$print = false;
			break;
		}

		
		//Si le filtrage est limité en nombre d'occurence, on supprime la partie correspondant au filtre
		if ($limitsearch)
			$haystack = str_replace($filtre, '', $haystack);
	}

	return $print;
}

function in_array_multiple($needles, $haystack) {
	if (!count($needles)) return false;
	$needle = array_pop($needles);
	return in_array($needle, $haystack) && (
			!count($needles) || 
			in_array_multiple($needles, $haystack));
}

function http_post($url, $post) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	$response = curl_exec($ch);
	return !curl_errno($ch) ? $response : curl_error($ch);
}


function removeAccents($txt){
    $txt = str_replace('œ', 'oe', $txt);
    $txt = str_replace('Œ', 'Oe', $txt);
    $txt = str_replace('æ', 'ae', $txt);
    $txt = str_replace('Æ', 'Ae', $txt);
    mb_regex_encoding('UTF-8');
    $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
    $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
    $txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
    $txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
    $txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
    $txt = mb_ereg_replace('[ďđ]', 'd', $txt);
    $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
    $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
    $txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
    $txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
    $txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
    $txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
    $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
    $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
    $txt = str_replace('Ĵ', 'J', $txt);
    $txt = str_replace('ĵ', 'j', $txt);
    $txt = str_replace('Ķ', 'K', $txt);
    $txt = str_replace('ķ', 'k', $txt);
    $txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
    $txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
    $txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
    $txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
    $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
    $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', 'o', $txt);
    $txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
    $txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
    $txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
    $txt = mb_ereg_replace('[śŝşš]', 's', $txt);
    $txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
    $txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
    $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
    $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
    $txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
    $txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
    $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
    $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
    $txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
    $txt = mb_ereg_replace('[źżž]', 'z', $txt);
    return $txt;
}