<?php

$result = hypeApps()->actions->execute(new \hypeJunction\Dropzone\Actions\uploadAction());
if (elgg_is_xhr()) {
	echo $result->output;
}
forward($result->getForwardURL());
