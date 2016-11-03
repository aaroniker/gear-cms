<?php

class table {

    protected $thead = [];
    protected $tfoot = [];
    protected $tbody = [];

    protected $section;

    protected $tableAttr = [];
    protected $colsLayout = [];
    protected $caption = [];

    function __construct($attributes = []) {

        $this->addSection('thead');

        #$attributes['class'][] = 'table';

        $this->tableAttr = $attributes;

        return $this;

    }

    public function addSection($section, $attributes = []) {

        if(in_array($section, ['thead', 'tfoot'])) {
            $this->section = $section;
        } else {
            $this->section = 'tbody';
        }

        $ref = $this->getCurrentSection();

        $ref['attr'] = $attributes;
        $ref['rows'] = [];

        $this->setCurrentSection($ref);

        return $this;

    }

    protected function getCurrentSection() {

        if($this->section === 'thead') {
            return $this->thead;
        } elseif($this->section === 'tfoot') {
            return $this->tfoot;
        } else {
            return $this->tbody;
        }

    }

    protected function setCurrentSection($section) {

        $this->{$this->section} = $section;

        return $this;

    }

    public function addColsLayout($cols) {

        if(!is_array($cols)) {

            $col2 = explode(',', $cols);
            $cols = [];

            foreach($col2 as $key=>$val) {
                $cols[]['width'] = $val;
            }

        }

        $this->colsLayout = $cols;

        return $this;

    }

    protected function getColsLayout() {

        $cols = '';
        foreach($this->colsLayout as $val) {
            $cols .= $this->addTag('col', $val, '', false);
        }

        return $this->addTag('colgroup', '', $cols);

    }

    protected function addTag($name, $attributes = [], $value = '', $end = true) {

        $attributes = $this->convertAttr($attributes);

        $endtag = ($end) ? '</'.$name.'>' : '';

        return '<'.$name.$attributes.'>'.$value.$endtag.PHP_EOL;

    }

    public function addCaption($value, $attributes = []) {

        $this->caption = ['value'=>$value, 'attr'=>$attributes];

        return $this;

    }

    protected function getCaption() {

        if(count($this->caption)) {
            return $this->addTag('caption', $this->caption['attr'], $this->caption['value']);
        }

        return '';

    }

    protected function convertAttr($attributes) {

        if(!count($attributes) || is_string($attributes)) {
            return '';
        }

        return html_convertAttribute($attributes);

    }

    public function addRow($attributes = []) {

        $ref = $this->getCurrentSection();

        $ref['rows'][] = [
            'attr' => $attributes,
            'cells' => []
        ];

        $this->setCurrentSection($ref);

        return $this;

    }

    public function addCell($value = '', $attributes = []) {

        $type = ($this->section === 'thead') ? 'th' : 'td';

        $cell = [
            'value' => $value,
            'type' => $type,
            'attr' => $attributes
        ];

        $ref = $this->getCurrentSection();

        $count = count( $ref['rows'] );
        $ref['rows'][$count-1]['cells'][] = $cell;

        $this->setCurrentSection($ref);

        return $this;

    }

    public function addCells($values, $attributes = []) {

        if(!is_array($values)) {
            return $this;
        }

        foreach($values as $value) {
            $this->addCell($value, $attributes);
        }

        return $this;

    }

    protected function getRowCells($cells) {

        $str = '';

        foreach( $cells as $cell ) {
            $str .= $this->addTag($cell['type'], $cell['attr'], $cell['value']);
        }

        return $str;

    }

    protected function getSection($sec, $tag) {

        if(!count($sec)) {
            return '';
        }

        $str = '';
        foreach($sec['rows'] as $row) {
            $str .= $this->addTag('tr', $row['attr'], $this->getRowCells($row['cells']));
        }

        return $this->addTag($tag, $sec['attr'], $str);

    }

    public function show() {

        $return = $this->getCaption();

        $return .= $this->getColsLayout();
        $return .= $this->getSection($this->thead, 'thead');
        $return .= $this->getSection($this->tfoot, 'tfoot');
        $return .= $this->getSection($this->tbody, 'tbody');

        $return =  $this->addTag('table', $this->tableAttr, PHP_EOL.$return);

        return $return;

    }

}

?>
