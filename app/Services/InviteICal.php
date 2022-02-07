<?php

namespace App\Services;

/**
 * 
 * Author: Ahmad Amin
 * Email : hello@evolutio.fit
 * Website: http://ahmadmain.com
 * 
 * Copyright (c) 2012 Ahmad Amin. All Rights Reserved.
 * 
 */
class InviteICal
{

    /**
     * The event start date
     * @var DateTime
     */
    private $_start;

    /**
     * The event end date
     * @var DateTime
     */
    private $_end;
    
    /**
     * The event _created date
     * @var DateTime
     */
    private $_created;

    /**
     * The name of the user the invite is coming from
     * @var string
     */
    private $_fromName;

    /**
     * Sender's email
     * @var string
     */
    private $_fromEmail;

    /**
     * The invite body content
     * @var string
     */
    private $_body;

    /**
     * 
     * The name of the event
     * @var string
     */
    private $_name;

    /**
     * The event location
     * @var string
     */
    private $_location;

    /**
     *
     * The content of the invite
     * @var string
     */
    private $_generated;

    /**
     * The list of guests
     * @var array
     */
    private $_guests = array();
    private $_savePath = "./invite/";

    /**
     * 
     * Not downloaded constant
     * 
     * @var const 
     */

    const NOT_DOWNLOADED = 5;

    /**
     * 
     * Downloaded constant
     * 
     * @var cost 
     * 
     */
    const DOWNLOADED = 10;

    public function __construct($uid = null)
    {
	if (null === $uid) {
	    $this->_uid = uniqid(rand(0, getmypid())) . "@evolutio.fit";
	} else {
	    $this->_uid = $uid . "@evolutio.fit";
	}
    
    
    
    $this->_location = 'Avenida quitapesares Nº 20 Nave 42A, 28670 Villa Viciosa de Odón (Madrid)​';
    $this->_fromName = 'evolutio.fit';
    $this->_fromEmail = 'info@evolutio.fit';

	if (!isset($_SESSION['calander_invite_downloaded'])) {
	    $_SESSION['calander_invite_downloaded'] = self::NOT_DOWNLOADED;
	}
	return $this;
    }

    public function getUID()
    {
	return $this->_uid;
    }

    /**
     * 
     * Set the event stat and end time.
     * 
     * @param DateTime $start
     * @param DateTime $end
     * @return \Invite 
     */
    public function setDate(\DateTime $start, \DateTime $end)
    {
	$this->setStartDate($start);
	$this->setEndDate($end);

	return $this;
    }

    /**
     * Set the start datetime
     * @param DateTime $start
     * @return \Invite 
     */
    public function setStart(\DateTime $start)
    {
	$this->_start = $start;
	return $this;
    }
    /**
     * Set the created_at datetime
     * @param DateTime $start
     * @return \Invite 
     */
    public function setCreated(\DateTime $time)
    {
	$this->_created = $time;
	return $this;
    }

    /**
     * Set the end datetime
     * @param DateTime $end
     * @return \Invite
     */
    public function setEnd(\DateTime $end)
    {
	$this->_end = $end;
	return $this;
    }

    /**
     * 
     * Set the of the even sender
     * 
     * @param string $email
     * @param string $name
     * @return \Invite 
     */
    public function setFrom($email, $name = null)
    {
	if (null === $name) {
	    $name = $email;
	}

	$this->_fromEmail = $email;
	$this->_fromName = $name;

	return $this;
    }

    /**
     * 
     * An alias of setFrom()
     * 
     * @param string $email
     * @param string $name
     * @return \Invite
     * 
     */
    public function setOrganizer($email, $name = null)
    {
	return $this->setFrom($email, $name);
    }

    /**
     * Set the name of the event
     * @param string $name
     * @return \Invite 
     */
    public function setName($name)
    {
	$this->_name = $name;
	return $this;
    }

    /**
     * An alies of setName
     * @param string $subject
     * @return \Invite 
     */
    public function setSubject($subject)
    {
	$this->setName($subject);
	return $this;
    }

    /**
     * 
     * An alies of setSubject()
     * 
     * @param string $summary
     * @return \Invite 
     */
    public function setSummary($summary)
    {
	$this->setSubject($summary);
	return $this;
    }

    /**
     * 
     * Set the invite body content
     * 
     * @param string $body
     * @return \Invite 
     */
    public function setBody($body)
    {
	$this->_body = $body;
	return $this;
    }

    /**
     * 
     * An alies of setBody().
     * @param string $desc
     * @return \Invite 
     */
    public function setDescription($desc)
    {
	$this->setBody($desc);
	return $this;
    }

    /**
     * 
     * Set the location where the event will take place
     * @param string $location
     * @return \Invite 
     */
    public function setLocation($location)
    {
	$this->_location = $location;
	return $this;
    }

    /**
     * An alies of setLocation()
     * @param string $place
     * @return string
     */
    public function setPlace($place)
    {
	return $this->setLocation($place);
    }

    /**
     * 
     * Add a guest to the list of attendees
     * @param type $email
     * @param type $name
     * @return \Invite 
     */
    public function addGuest($email, $name = null)
    {
	if (null === $name) {
	    $name = $email;
	}

	if (!isset($this->_guest[$email])) {
	    $this->_guests[$email] = $name;
	}

	return $this;
    }

    /**
     *
     * Remove a guest from the list
     * 
     * @param string $email
     * @return \Invite 
     */
    public function removeGuest($email)
    {
	if (isset($this->_guests[$email])) {
	    unset($this->_guests[$email]);
	}

	return $this;
    }

    /**
     * 
     * An alies of remove guest
     * 
     * @param string $email
     * @return \Invite
     */
    public function removeAttendee($email)
    {
	return $this->removeGuest($email);
    }

    /**
     * 
     * Clear a the guest list
     * @return \Invite 
     */
    public function clearGuests()
    {
	$this->_guests = array();
	return $this;
    }

    /**
     * 
     * An alies of clear guests
     * @return \Invite 
     */
    public function clearAttendees()
    {
	return $this->clearGuests();
    }

    /**
     * 
     * Get all guest that's currently set for an this events.
     * @return array
     * 
     */
    public function getGuests()
    {
	return $this->_guests;
    }

    /**
     * An alies of getGuests();
     * @return array
     */
    public function getAttendees()
    {
	return $this->getGuests();
    }

    /**
     * An Alies of add guest
     * @param string $email
     * @param string $name
     * @return Invite 
     */
    public function addAttendee($email, $name = null)
    {
	return $this->addGuest($email, $name);
    }

    /**
     * 
     * Get the location where the event will be held
     * @return type 
     */
    public function getLocation()
    {
	return $this->_location;
    }

    /**
     * An alies of getLocation()
     * @return string
     */
    public function getPlace()
    {
	return $this->getLocation();
    }

    /**
     * 
     * Get the event name
     * @return string
     */
    public function getName()
    {
	return $this->_name;
    }

    /**
     * An alies of getName();
     * @return string
     */
    public function getSummary()
    {
	return $this->getName();
    }

    /**
     * Get the current body content
     * @return string
     */
    public function getBody()
    {
	return $this->_body;
    }

    /**
     * An alies of getBody()
     * @return string
     */
    public function getDescription()
    {
	return $this->getBody();
    }

    /**
     * Just to do it.
     * @return \Invite 
     */
    public function __toString()
    {
	return $this;
    }

    /**
     * Get the name of the invite sender
     * @return string
     */
    public function getFromName()
    {
	return $this->_fromName;
    }

    /**
     * Get the email where the email will be sent from
     * @return string
     */
    public function getFromEmail()
    {
	return $this->_fromEmail;
    }

    /**
     * Get the start time set for the even
     * @return string
     */
    public function getStart($formatted = null)
    {
	if (null !== $formatted) {
	    return $this->_start->format("Ymd\THis\Z");
	}

	return $this->_start;
    }
    /**
     * Get the _created time set for the even
     * @return string
     */
    public function getCreated($formatted = null)
    {
	if (null !== $formatted) {
	    return $this->_created->format("Ymd\THis\Z");
	}

	return $this->_created;
    }

    /**
     * Get the end time set for the event
     * @return string
     */
    public function getEnd($formatted = null)
    {
	if (null !== $formatted) {
	    return $this->_end->format("Ymd\THis\Z");
	}
	return $this->_end;
    }

    /**
     * 
     * Call this function to download the invite. 
     */
    public function download()
    {
	$_SESSION['calander_invite_downloaded'] = self::DOWNLOADED;
	$generate = $this->_generate();
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"invite.ics\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . strlen($generate));
	print $generate;
    }

    /**
     * 
     * Save the invite to a file
     * 
     * @param string $path
     * @param string $name
     * @return \Invite 
     * 
     */
    public function save($path = null, $name = null)
    {
	if (null === $path) {
	    $path = $this->_savePath;
	}

	if (null === $name) {
	    $name = $this->getUID() . '.ics';
	}

    if (($output = $this->getInviteContent()) == TRUE) {
      $fileName = 'calendars/cita-'. $this->getUID() . '.ics';
      $fileName = 'calendars/cita.ics';
      $path = storage_path('app/' . $fileName);
      $storage = \Illuminate\Support\Facades\Storage::disk('local');
      $storage->put($fileName, $output);
      return $path;
    } else {
      return null;
    }
    
    
	// create path if it doesn't exist
	if (!is_dir($path)) {
	    try {
		mkdir($path, 0777, TRUE);
	    } catch (Exception $e) {
		die('Unabled to create save path.');
	    }
	}

	if (($data = $this->getInviteContent()) == TRUE) {
	    try {
		$handler = fopen($path . $name, 'w+');
		$f = fwrite($handler, $data);
		fclose($handler);

		// saving the save name
		$_SESSION['savepath'] = $path . $name;
	    } catch (Exception $e) {
		die('Unabled to write invite to file.');
	    }
	}

	return $this;
    }

    /**
     * Get the saved invite path
     * @return string|boolean 
     */
    public static function getSavedPath()
    {
	if (isset($_SESSION['savepath'])) {
	    return $_SESSION['savepath'];
	}

	return false;
    }

    /**
     * 
     * Check to see if the invite has been downloaded or not
     * 
     * @return boolean 
     * 
     */
    public static function isDownloaded()
    {
	if ($_SESSION['calander_invite_downloaded'] == self::DOWNLOADED) {
	    return true;
	}

	return false;
    }

    public function isValid()
    {
	if ($this->_start || $this->_end || $this->_name ||
		$this->_fromEmail || $this->_fromName || is_array($this->_guests)) {
	    return true;
	}

	return false;
    }

    /**
     * 
     * Get the content of for and invite. Returns false if the invite
     * was unable to be generated.
     * @return string|boolean 
     */
    public function getInviteContent()
    {
	if (!$this->_generated) {
	    if ($this->isValid()) {
		if ($this->_generate()) {
		    return $this->_generated;
		}
	    }
	    return false;
	}

	return $this->_generated;
    }

    /**
     *
     * Generate the content for the invite.
     * 
     * @return \Invite 
     * 
     */
    public function generate()
    {
	$this->_generate();
	return $this;
    }

    /**
     * 
     * The function generates the actual content of the ICS
     * file and returns it.
     * 
     * @return string|bool
     */
    private function _generate()
    {
	if ($this->isValid()) {
      $update = new \DateTime('2022-03-16 17:00:00');

	    $content = "BEGIN:VCALENDAR\n";
        $content .= "PRODID:".time()."\n";
	    $content .= "VERSION:2.0\n";
	    $content .= "CALSCALE:GREGORIAN\n";
	    $content .= "METHOD:PUBLISH\n";
        
	    $content .= "BEGIN:VEVENT\n";
	    $content .= "UID:{$this->getUID()}\n";
	    $content .= "DTSTART:{$this->getStart(true)}\n";
	    $content .= "DTEND:{$this->getEnd(true)}\n";
	    $content .= "DTSTAMP:{$this->getStart(true)}\n";
	    $content .= "ORGANIZER;CN={$this->getFromName()}:mailto:{$this->getFromEmail()}\n";
        $content .= "CREATED:{$this->getCreated(true)}\n";
	    $content .= "DESCRIPTION:{$this->getDescription()}\n";
	    $content .= "LAST-MODIFIED:{$update->format("Ymd\THis\Z")}\n";
	    $content .= "LOCATION:{$this->getLocation()}\n";
	    $content .= "SUMMARY:{$this->getName()}\n";
	    $content .= "SEQUENCE:0\n";
	    $content .= "STATUS:CONFIRMED\n";
	    $content .= "TRANSP:OPAQUE\n";
	    $content .= "END:VEVENT\n";
        
	    $content .= "END:VCALENDAR";

	    $this->_generated = $content;
	    return $this->_generated;
	}

	return false;
    }

}