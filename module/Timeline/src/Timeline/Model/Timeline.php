<?php
namespace Timeline\Model;

// Add these import statements
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Timeline
{
    public $id;
    public $start_date;
    public $end_date;
    public $headline;
    public $text;
    public $media;
    public $media_credit;
    public $media_caption;
    public $media_thumbnail;
    public $type;
    public $id_tag;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id_timeline'])) ? $data['id_timeline'] : null;
        $this->start_date = (!empty($data['start_date'])) ? $data['start_date'] : null;
        $this->end_date  = (!empty($data['end_date'])) ? $data['end_date'] : null;
        $this->headline     = (!empty($data['headline'])) ? $data['headline'] : null;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->media  = (!empty($data['media'])) ? $data['media'] : null;
        $this->media_credit    = (!empty($data['media_credit'])) ? $data['media_credit'] : null;
        $this->media_caption = (!empty($data['media_caption'])) ? $data['media_caption'] : null;
        $this->media_thumbnail  = (!empty($data['media_thumbnail'])) ? $data['media_thumbnail'] : null;
        $this->type     = (!empty($data['type'])) ? $data['type'] : null;
        $this->id_tag = (!empty($data['id_tag'])) ? $data['id_tag'] : null;
    }
    
    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
    
            $inputFilter->add(array(
                'name'     => 'id_timeline',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
    
            $inputFilter->add(array(
                'name'     => 'headline',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}