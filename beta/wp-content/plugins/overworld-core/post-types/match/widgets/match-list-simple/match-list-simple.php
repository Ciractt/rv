<?php

class OverworldCoreClassMatchListSimpleWidget extends OverworldCoreClassWidget {
	protected $params;

	public function __construct() {
		parent::__construct(
			'edgtf_match_list_simple_widget', // Base ID
			esc_html__('Overworld Match List Simple', 'overworld-core'), // Name
			array('description' => esc_html__('Display matches from Match Post', 'overworld-core')) // Args
		);

		$this->setParams();
	}

	protected function setParams() {
		$this->params = array(
			array(
				'name'  => 'title',
				'type'  => 'textfield',
				'title' => esc_html__('Title', 'overworld-core')
			),
			array(
				'name'  => 'number_of_items',
				'type'  => 'textfield',
				'title' => esc_html__('Number of matches', 'overworld-core')
			),
			array(
				'name'  => 'category',
				'type'  => 'textfield',
				'title' => esc_html__('One-Category Match List', 'overworld-core'),
			),
            array(
				'name'  => 'selected_matches',
				'type'  => 'textfield',
				'title' => esc_html__('Show Only Matches with Listed IDs', 'overworld-core'),
			),
			array(
				'name'    => 'order_by',
				'type'    => 'dropdown',
				'title'   => esc_html__('Order By', 'overworld-core'),
				'options' => array(
					'title' => esc_html__('Title', 'overworld-core'),
					'date'  => esc_html__('Date', 'overworld-core')
				)
			),
			array(
				'name'    => 'order',
				'type'    => 'dropdown',
				'title'   => esc_html__('Order', 'overworld-core'),
				'options' => array(
					'ASC'  => esc_html__('ASC', 'overworld-core'),
					'DESC' => esc_html__('DESC', 'overworld-core')
				)
			)
		);
	}

	public function widget($args, $instance) {
		extract($args);

		//prepare variables
		$content = '';
		$params = array();

		//is instance empty?
		if (is_array($instance) && count($instance)) {
			//generate shortcode params
			foreach ($instance as $key => $value) {
				$params[$key] = $value;
			}
		}

		echo '<div class="widget edgtf-match-list-simple-widget">';

		if (!empty($instance['title'])) {
            echo overworld_edge_get_module_part( $args['before_title'] . $instance['title'] . $args['after_title'] );
		}

		echo overworld_edge_execute_shortcode('edgtf_match_list_simple', $params);

		echo '</div>'; //close edgtf-match-list-widget
	}

}
