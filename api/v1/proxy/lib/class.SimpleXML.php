<?php
/**
 * Class SimpleXML
 * Permet de manipuler des donn�es au format XML
 *
 * @author Arnaud NICOLAS - arno06@gmail.com
 * @version .2
 * @package CBi
 * @subpackage data
 * @todo Voir pour ajouter une gestion de l'encodage des caract�res (cas o� le xml est en utf-8)
 */
abstract class SimpleXML
{
	/**
	 * Charset � utiliser pour l'encodage
	 * @var String
	 */
	static public $encoding = "ISO-8859-1";
	
	/**
	 * M�thode de r�cup�ration d'un tableau associatif multidimensionnel � partir d'un contenu �crit au format XML
	 * @param String $pString		Contenu XML
	 * @return Array
	 */
	static public function decode ($pString)
	{
		$pString = preg_replace("/^\<\?xml version\=\"[0-9]{1}\.[0-9]{1}\" encoding\=\"[a-z0-9\-]*\"[\s]*\?\>/", "", $pString);
		$pString = preg_replace("/[\n\r]+/","",$pString);
		$pString = preg_replace("/\>[\t\s]+\</","><", $pString);
		return self::getArrayFromNode($pString);
	}
	
	
	/**
	 * M�thode d'encodage d'un tableau en donn�es format�es en XML
	 * @param Array $pTableau		Tableau des donn�es
	 * @return String
	 */
	static public function encode(array $pTableau)
	{
		$return = "<?xml version=\"1.0\" encoding=\"".self::$encoding."\"?>";
		$return .= self::getRecurciveNodes($pTableau);
		return $return;
	}
	
	/**
	 * M�thode permettant de d�coder de facon r�cursive un noeud XML en tableau
	 * @param String $pString		Noeux XML
	 * @return Array
	 */
	static private function getArrayFromNode($pString)
	{
		$return = array();
		while(preg_match("/^\<([a-z0-9:]{1,})([^>]*)?[\s]*[\/]{0,1}\>(.*)/i", $pString, $m))
		{
			if(preg_match("/\<\/".$m[1]."\>/", $m[3], $extract))
            {
                $pString = strstr($m[3], "</".$m[1].">");
                $pString = substr_replace($pString,'', 0, (strlen ($m[1])+3));
                $childs = substr(strrev(strstr(strrev($m[3]), strrev("</".$m[1].">"))), 0, -strlen("</".$m[1].">"));
            }
            else
            {
                $pString = $m[3];
                $childs = "";
            }
			$node = array();
			preg_match_all('/(([a-z0-9\_\-]*)\=\"([^"]*)?\"){1}/i', $m[2], $p);
			for($i = 0, $max = count($p[0]); $i<$max; ++$i)
			{
				if(is_string($p[2][$i])&&!empty($p[2][$i]))
					$node[$p[2][$i]] = $p[3][$i];
			}
			if(!empty($childs)&&(preg_match('/^\<\!\[CDATA\[(.*)?\]\]\>$/',$childs,$c)||(preg_match('/^([^<>]*)$/',$childs,$c))))
				$node["nodeValue"] = $c[1];
			$node = array_merge($node,self::getArrayFromNode($childs));
			if(!isset($return[$m[1]]))
				$return[$m[1]] = $node;
			elseif(is_numeric(key($return[$m[1]])))
				$return[$m[1]][] = $node;
			elseif(is_string(key($return[$m[1]])))
			{
				$f = $return[$m[1]];
				$return[$m[1]] = array($f,$node);
			}
		}
		return $return;
	}
	
	
	/**
	 * M�thode de parsage r�cursif d'un tableau en noeuds XML
	 * @param String $pTableau		Tableau des donn�es
	 * @return String
	 */
	static private function getRecurciveNodes($pTableau)
	{
		$nodes = "";
		foreach($pTableau as $nodeName=>$node)
		{
			if(is_string($node))
			{
				$nodes .= "<".$nodeName.">";
				$nodes .= self::getEscapedString($node);
				$nodes .= "</".$nodeName.">";
				continue;
			}
			if(!is_array($node))
				continue;
			if(is_numeric(key($node)))
			{
				for($i = 0, $max = count($node); $i<$max; ++$i)
					$nodes .= self::getNode($nodeName, $node[$i]);
			}elseif(is_string(key($node)))
				$nodes .= self::getNode($nodeName, $node);
		}
		return $nodes;
	}
	
	/**
	 * M�thode de r�cup�ration d'un noeud XML et de ses enfants
	 * @param String $pNodeName		Nom du noeud � r�cup�rer
	 * @param Array $pTableau		Tableau des proprietes et enfants du noeud
	 * @return String
	 */
	static private function getNode($pNodeName, $pTableau)
	{
		$infos = "<".$pNodeName;
		$childs = array();
		foreach($pTableau as $name=>$value)
		{
			if($name=="nodeValue")
			{
				$nodeValue = $value;
				continue;
			}
			if(!is_array($value))
				$infos .= " ".$name."=\"".$value."\"";
			else
				$childs[$name] = $value;
		}
		$infos .= ">";
		if(isset($nodeValue))
			$infos .= self::getEscapedString($nodeValue);
		$infos .= self::getRecurciveNodes($childs);
		$infos .= "</".$pNodeName.">";
		return $infos;
	}
	
	/**
	 * M�thode de r�cup�ration d'une chaine de caract�res dont on souhaite prot�ger des caract�res d'�chappements (balises...)
	 * @param String $pString		Chaine � �chapp�e
	 * @return String
	 */
	static private function getEscapedString($pString)
	{
		if(preg_match("/(<|>)/",$pString, $m))
			return "<![CDATA[".$pString."]]>";
		else
			return $pString;
	}
}
?>