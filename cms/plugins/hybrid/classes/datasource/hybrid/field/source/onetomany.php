<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class DataSource_Hybrid_Field_Source_OneToMany extends DataSource_Hybrid_Field {
	
	public $from_ds = NULL;

	/**
	 * 
	 * @param array $row
	 * @param integr $fid
	 * @param integer $recurse
	 * @return array
	 */
	protected static function _fetch_related_widget( $widget, $row, $fid, $recurse, $key = 'ids', $fetch = FALSE)
	{
		$widget_id = $widget->doc_fetched_widgets[$fid];
		
		if( empty($widget_id) ) return NULL;

		$widget = Context::instance()->get_widget($widget_id);
		
		if(!$widget)
		{
			$widget = Widget_Manager::load($widget_id);
		}
		
		if($widget === NULL) return array();

		$doc_ids = explode(',', $row[$fid]);

		$widget->{$key} = $doc_ids;
		
		if($fetch === FALSE)
		{
			return $widget->get_documents( $recurse - 1);
		}
		else
		{
			return $widget->fetch_data();
		}
	}
}