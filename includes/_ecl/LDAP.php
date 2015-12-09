<?php

/*
* @update $Id: ldap.class.php,v 1.1 2003/09/14 20:35:44 adadou Exp $
* @version 1.0
* @author Aur?lien DADOU (Pimouss) <aurelien.dadou@ecl2005.ec-lyon.fr>
* @package common
*/
class ldap
{
	/**
	* h?tes de la base LDAP sous la forme 'ip1, ip2, ip3, ...'
	* @var string
	* @access private
	*/
	var $hosts = '';
	/**
	* dn de l'utilisateur LDAP
	* @var string
	* @access private
	*/
	var $userdn = '';
	/**
	* mot de passe de l'utilisateur LDAP
	* @var string
	* @access private
	*/
	var $password = '';
	/**
	* dn de la base LDAP
	* @var string
	* @access private
	*/
	var $basedn = '';
	/**
	* mode debug ou pas
	* @var boolean
	* @access private
	*/
	var $debug = 0;
	/**
	* idem : 'yes' (arr?t et message), 'no' (pas de gestion des erreurs), 'report' (ignore les erreurs mais affiche un avertissement)
	* @var string
	* @access private
	*/
	var $haltOnError = 'yes'; // 'yes' (arr?t et message), 'no' (pas de gestion des erreurs), 'report' (ignore les erreurs mais affiche un avertissement)

	/**
	* erreur courante
	* @var string
	* @access private
	*/
	var $error = '';
	/**
	* ressource de la connexion
	* @var mixed
	* @access private
	*/
	var $linkId = 0;
	/**
	* ressource du r?sultat de la requ?te
	* @var mixed
	* @access private
	*/
	var $queryId = 0;
	/**
	* ressource de la derni?re entr?e
	* @var mixed
	* @access private
	*/
	var $entryId = false;

	/**
	* r?sultat de la requ?te
	* @var array
	* @access public
	*/
	var $record  = array();
	/**
	* num?ro de ligne courante
	* @var int
	* @access private
	*/
	var $row;
	/**
	* nombre de lignes du r?sultat
	* @var int
	* @access public
	*/
	var $numRows;



	/**
	* constructeur. D?finit les valeurs par d?faut ? l'aide de constantes d?finies dans /include/common/config.inc.php
	* @access public
	* @since 1.0
	* @update 13/08/2003
	*/
	function ldap($hosts, $password, $basedn, $userdn)
		{
			$this->hosts = $hosts;
			$this->password = $password;
			$this->basedn = $basedn;
			$this->userdn = $userdn;
		} // end constructor

	/**
	* connexion ? la base LDAP
	*
	* @param string $hosts serveurs auxquels se connecter si diff?rent de la valeur par d?faut
	* @param string $userdn dn de l'utilisateur si diff?rent de la valeur par d?faut
	* @param string $password mot de passe si diff?rent de la valeur par d?faut
	* @return mixed false si la connexion a ?chou?e, la ressource si la connexion a r?ussi
	* @access public
	* @since 1.0
	* @update 19/08/2003
	*/
	function connect($hosts = '', $userdn = '', $password = '')
		{
			// Si les variables sont vides, on utilise les valeurs par d?faut
			if ('' != $hosts) $this->hosts = $hosts;
			if ('' != $userdn) $this->userdn = $userdn;
			if ('' != $password) $this->password = $password;
			
			// R?cup?ration de la liste des serveurs diponibles
			$hosts = explode(', ', $this->hosts);
		
			$connected = false;
			// On ouvre une nouvelle connection LDAP si il n'y en a pas
			if (!$this->linkId)
				{
					foreach ($hosts as $host)
						{
							if($this->linkId)
								{
									@ldap_close($this->linkId);
								}
							// Connexion au serveur sur le port 389 (port par d?faut)
							$this->linkId = @ldap_connect($host); 
							if ($this->linkId)
								{
									// Etablissement de la liaison au serveur
									$connected = @ldap_bind($this->linkId, $this->userdn, $this->password); 
									if ($connected)
										{
											return $this->linkId;
										}
								}
						}
					$this->error = ldap_error($this->linkId);
					$this->halt('ldap::connect() : Impossible de se connecter aux serveurs LDAP.<br />');
					return false;
				}
			// S'il n'y a eu aucune erreur, on retourne le lien
			return $this->linkId;
		} // end func
		

	/**
	* Cloture de la connexion ? la base LDAP
	* @access public
	* @since 1.0
	* @update 19/08/2003
	*/
	function close()
		{
			if ($this->queryId)
				{
					$this->free();
				}
			@ldap_unbind($this->linkId);
		} // end func
		

	/**
	* lib?re la m?moire
	* @access private
	* @since 1.0
	* @update 19/08/2003
	*/
	function free()
		{
			@ldap_free_result($this->queryId);
			$this->record = array();
			$this->queryId = 0;
		} //end func

	/**
	* Execution d'une recherche dans l'annuaire LDAP
	* @param string $query filtre ? appliquer sous la forme '(&(var1=*value1*)(var2=*value2*)...)'. Vous avez la possibilit? de mettre des op?rateurs du type & ou | pour affiner des recherches.
	* @return mixed false si la requ?te a ?chou?e, la ressource si la requ?te est correcte
	* @access public
	* @since 1.0
	* @update 19/08/2003
	*/
	function query($query)
		{
			if ($query == '' OR !$this->connect())
				{
					return false;
				}
			if ($this->queryId)
				{
					$this->free(); // Si une requ?te a d?j? ?t? ex?cut?, on lib?re l'espace m?moire qu'elle occupait
				}
			if ($this->debug)
				{
					echo 'Ex?cution de la requ?te : '.$query.'<br />';
				}
			$this->queryId = @ldap_search($this->linkId, $this->basedn, $query);
			$this->error = ldap_error($this->linkId);
			if (!$this->queryId)
				{
					$this->halt('ldap::query() : Erreur dans une recherche LDAP : '.$query);
					return 0;
				}
			$this->numRows = @ldap_count_entries($this->linkId, $this->queryId);
			$this->row = 0;
			$this->entryId = false;
			return $this->queryId;
		} //end func


	/**
	* Ordonne les entrées LDAP
	* @param string $filtre : nom du champ avec lequel le filtre se fait
	* @return void false si la requ?te a ?chou?e, la ressource si la requ?te est correcte
	* @access public
	* @since 1.0
	* @update 18/07/2014
	*/
	function order($filtre)
		{
			if (!$this->queryId)
				{
					$this->halt('ldap::order() : Erreur dans le trie LDAP');
					return false;
				}
			return ldap_sort($this->linkId, $this->queryId, $filtre);
		} //end func

	/**
	* parcourt le r?sultat d'une requ?te
	* @return boolean indique s'il y a encore un r?sultat ou non
	* @access public
	* @since 1.0
	* @update 19/08/2003
	*/
	function nextRecord()
		{
			if (!$this->queryId)
				{
					$this->halt('Aucune requ?te n\'a ?t? effectu?. Il n\'y a pas de r?sultat disponible.');
					return false;
				}
			if ($this->row < $this->numRows and !$this->row)
				{
					$this->entryId = @ldap_first_entry($this->linkId, $this->queryId);
				}
			else if ($this->row < $this->numRows and $this->row)
				{
					$this->entryId = @ldap_next_entry($this->linkId, $this->entryId);
				}
			else
				{
					$this->free();
					return false;
				}
				
			$this->record = array();
			$attributes = array();
			$attributes = @ldap_get_attributes($this->linkId, $this->entryId);
			for($i=0; $i<$attributes['count']; $i++)
				{
					//array_push($this->record, array($attributes[$i] => @ldap_get_values($this->linkId, $this->entryId, $attributes[$i])));
					$this->record[strtolower($attributes[$i])] = @ldap_get_values($this->linkId, $this->entryId, $attributes[$i]);
				}
								
			$this->row++;
			return true;
				
		} // end func


	/**
	* Retourne le nombre de lignes du r?sultat d'une requ?te
	* @return int nombre de lignes
	* @access public
	* @since 1.1
	* @update 03/06/2002
	*/
	function numRows()
		{
			return $this->numRows;
		}//end func


	/**
	* G?re les erreurs
	* @param $msg message d'erreur
	* @access private
	* @since 1.0
	* @update 19/08/2003
	*/
	function halt($msg)
		{
			$this->error = @ldap_error($this->linkId);
			if ($this->haltOnError == 'no')
				{
					return;
				}
			echo 'Il y a un probl?me au niveau de la base LDAP.<br />';
			echo $msg.'<br />';
			if ($this->error!='')
				{
					echo 'Erreur LDAP : '.$this->error.'<br />';
				}
			if ($this->haltOnError != 'report')
				{
					die('<b>Session arr?t?e</b>');
				}
		}// end func


} // end class
