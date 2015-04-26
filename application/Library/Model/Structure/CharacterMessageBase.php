<?php
/**
* AUTO-GENERATED
* DO NOT EDIT
*/
class Model_Structure_CharacterMessageBase
{
    protected $m_character_message_id;
    protected $m_sender_user_id;
    protected $m_sender_character_id;
    protected $m_recipient_character_id;
    protected $m_message_title;
    protected $m_message_text;
    protected $m_date_created;
    protected $m_viewed;
    protected $m_date_viewed;
    protected $m_character_message_id_Orig;

    public function __construct($arrData = null)
    {
        if (isset($arrData)) {
            $this->loadFromArray($arrData);
        }
        else {
            $this->setViewed(0);
        }
        return;
    }
    public function CharacterMessageBase($arrData = null)
    {
        $this->__construct($arrData);
        return;
    }

    public function getCharacterMessageId()
    {
        return $this->m_character_message_id;
    }
    public function setCharacterMessageId($value)
    {
        $this->m_character_message_id = $value;
        $this->setOrigCharacterMessageId($value);
        return;
    }

    public function getSenderUserId()
    {
        return $this->m_sender_user_id;
    }
    public function setSenderUserId($value)
    {
        $this->m_sender_user_id = $value;
        return;
    }

    public function getSenderCharacterId()
    {
        return $this->m_sender_character_id;
    }
    public function setSenderCharacterId($value)
    {
        $this->m_sender_character_id = $value;
        return;
    }

    public function getRecipientCharacterId()
    {
        return $this->m_recipient_character_id;
    }
    public function setRecipientCharacterId($value)
    {
        $this->m_recipient_character_id = $value;
        return;
    }

    public function getMessageTitle()
    {
        return $this->m_message_title;
    }
    public function setMessageTitle($value)
    {
        $this->m_message_title = $value;
        return;
    }

    public function getMessageText()
    {
        return $this->m_message_text;
    }
    public function setMessageText($value)
    {
        $this->m_message_text = $value;
        return;
    }

    public function getDateCreated()
    {
        return $this->m_date_created;
    }
    public function setDateCreated($value)
    {
        $this->m_date_created = $value;
        return;
    }

    public function getViewed()
    {
        return $this->m_viewed;
    }
    public function setViewed($value)
    {
        $this->m_viewed = $value;
        return;
    }

    public function getDateViewed()
    {
        return $this->m_date_viewed;
    }
    public function setDateViewed($value)
    {
        $this->m_date_viewed = $value;
        return;
    }

    public function getOrigCharacterMessageId()
    {
        return $this->m_character_message_id_Orig;
    }
    public function setOrigCharacterMessageId($value)
    {
        if (isset($this->m_character_message_id_Orig)) { return; }
        $this->m_character_message_id_Orig = $value;
        return;
    }

    public function loadFromArray($arrValues)
    {
        $this->setCharacterMessageId($arrValues['character_message_id']);
        $this->setSenderUserId($arrValues['sender_user_id']);
        $this->setSenderCharacterId($arrValues['sender_character_id']);
        $this->setRecipientCharacterId($arrValues['recipient_character_id']);
        $this->setMessageTitle($arrValues['message_title']);
        $this->setMessageText($arrValues['message_text']);
        $this->setDateCreated($arrValues['date_created']);
        $this->setViewed($arrValues['viewed']);
        $this->setDateViewed($arrValues['date_viewed']);
        return;
    }

    public function updateFromArray($arrValues)
    {
        foreach ($arrValues as $key=>$val) {
            switch ($key) {
                case 'character_message_id':
                    $this->setCharacterMessageId($val);
                    break;
                case 'sender_user_id':
                    $this->setSenderUserId($val);
                    break;
                case 'sender_character_id':
                    $this->setSenderCharacterId($val);
                    break;
                case 'recipient_character_id':
                    $this->setRecipientCharacterId($val);
                    break;
                case 'message_title':
                    $this->setMessageTitle($val);
                    break;
                case 'message_text':
                    $this->setMessageText($val);
                    break;
                case 'date_created':
                    $this->setDateCreated($val);
                    break;
                case 'viewed':
                    $this->setViewed($val);
                    break;
                case 'date_viewed':
                    $this->setDateViewed($val);
                    break;
                default:
                    break;
            }
        }
        return;
    }

    public function getAsArray()
    {
        $arrValues = array();
        $arrValues['character_message_id'] = $this->getCharacterMessageId();
        $arrValues['sender_user_id'] = $this->getSenderUserId();
        $arrValues['sender_character_id'] = $this->getSenderCharacterId();
        $arrValues['recipient_character_id'] = $this->getRecipientCharacterId();
        $arrValues['message_title'] = $this->getMessageTitle();
        $arrValues['message_text'] = $this->getMessageText();
        $arrValues['date_created'] = $this->getDateCreated();
        $arrValues['viewed'] = $this->getViewed();
        $arrValues['date_viewed'] = $this->getDateViewed();
        return $arrValues;
    }

    public function validateInsert(&$arrErrors)
    {
        return true;
    }

    public function validateUpdate(&$arrErrors)
    {
        return true;
    }
}
