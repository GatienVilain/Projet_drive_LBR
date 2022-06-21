<?php

namespace Application\Tools;

class CustomSort
{
	//renvoie une liste des id_fichier triés par utilisateurs
	function sort_by_user(array $data, array $username) {
		$result = array();
		
		foreach ($data as $value) {
			if (in_array($value->getAuthorName(),$username)){
				$result[] = $value;
			}
		}
		
		return $result;
	}

	//renvoie une liste des id_fichier triés par extensions
	function sort_by_extension(array $data, array $extension) {
		$result = array();
		
		foreach ($data as $value) {
			if (in_array($value->getFileExtension(),$extension)){
				$result[] = $value;
			}
		}
		
		return $result;
	}
	
	//renvoie une liste des id_fichier triés par tag, par défaut en mode union, "intersection" pour le mode intersection
	function sort_by_tag(array $data, array $tag, string $option = "union") {
		$result = array();
		
		if ($option == "intersection") {
			foreach ($data as $value) {
				if (array_values(array_intersect($tag,$value->getTags())) == array_values($tag)){
					$result[] = $value;
				}
			}
		}
		else {
			foreach ($data as $value) {
				if (count(array_intersect($tag,$value->getTags())) > 0){
					$result[] = $value;
				}
			}
		}
		
		return $result;
	}
	
	//renvoie une liste des id_fichier triés par ordre alphabétique, par défaut dans l'ordre croissant, "desc" pour l'ordre décroissant
	function sort_by_alphabetical(array $data, string $option = "asc") {
		
		if ($option == "desc") {
			usort($data, function($a, $b) {
				return strtolower($b->getFilename()) <=> strtolower($a->getFilename());
			});
		}
		else {
			usort($data, function($a, $b) {
				return strtolower($a->getFilename()) <=> strtolower($b->getFilename());
			});
		}
		
		return $data;
	}
	
	//renvoie une liste des id_fichier triés par date de modification, par défaut dans l'ordre croissant, "desc" pour l'ordre décroissant
	function sort_by_date(array $data, string $option = "asc") {
		
		if ($option == "desc") {
			usort($data, function($a, $b) {
				return strtolower($b->getModificationDate()) <=> strtolower($a->getModificationDate());
			});
		}
		else {
			usort($data, function($a, $b) {
				return strtolower($a->getModificationDate()) <=> strtolower($b->getModificationDate());
			});
		}
		
		return $data;
	}
}