<?php
/**
 * Class plugins_textmulti_db
 */
class plugins_textmulti_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'textmultis':
					$query = 'SELECT 
								id_textmulti,
								title_textmulti,
								desc_textmulti
							FROM mc_textmulti ms
							LEFT JOIN mc_textmulti_content msc USING(id_textmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE ml.id_lang = :lang
							  AND ms.module_textmulti = :module
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							ORDER BY ms.order_textmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'activetextmultis':
					$query = 'SELECT 
								id_textmulti,
								title_textmulti,
								desc_textmulti
							FROM mc_textmulti ms
							LEFT JOIN mc_textmulti_content msc USING(id_textmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE iso_lang = :lang
							  AND ms.module_textmulti = :module_textmulti
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							  AND published_textmulti = 1
							ORDER BY order_textmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'textmultiContent':
					$query = 'SELECT ms.*, msc.*
							FROM mc_textmulti ms
							JOIN mc_textmulti_content msc USING(id_textmulti)
							JOIN mc_lang ml USING(id_lang)
							WHERE ms.id_textmulti = :id';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'textmultiContent':
					$query = 'SELECT * FROM mc_textmulti_content WHERE id_textmulti = :id AND id_lang = :id_lang';
					break;
				case 'lasttextmulti':
					$query = 'SELECT * FROM mc_textmulti ORDER BY id_textmulti DESC LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'textmulti':
				$query = "INSERT INTO mc_textmulti(module_textmulti, id_module, order_textmulti) 
						SELECT :module, :id_module, COUNT(id_textmulti) FROM mc_textmulti WHERE module_textmulti = '".$params['module']."'";
				break;
			case 'textmultiContent':
				$query = 'INSERT INTO mc_textmulti_content(id_textmulti, id_lang, title_textmulti, desc_textmulti, published_textmulti)
						VALUES (:id_textmulti, :id_lang, :title_textmulti, :desc_textmulti, :published_textmulti)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'textmultiContent':
				$query = 'UPDATE mc_textmulti_content
						SET 
							title_textmulti = :title_textmulti,
							desc_textmulti = :desc_textmulti,
							published_textmulti = :published_textmulti
						WHERE id_textmulti_content = :id 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$query = 'UPDATE mc_textmulti 
						SET order_textmulti = :order_textmulti
						WHERE id_textmulti = :id_textmulti';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	protected function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'textmulti':
				$query = 'DELETE FROM mc_textmulti WHERE id_textmulti IN('.$params['id'].')';
				$params = [];
				break;
			default:
				return false;
		}
		
		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
	}
}