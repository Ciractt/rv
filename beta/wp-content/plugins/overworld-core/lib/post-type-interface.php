<?php

namespace OverworldCore\Lib;

/**
 * interface PostTypeInterface
 * @package OverworldCore\Lib;
 */
interface PostTypeInterface {
	/**
	 * @return string
	 */
	public function getBase();
	
	/**
	 * Registers custom post type with WordPress
	 */
	public function register();
}