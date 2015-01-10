<?php

namespace VideoConference\LogInBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VideoConferenceLogInBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
