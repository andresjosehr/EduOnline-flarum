<?php namespace Davis\CustomHeader\Listeners;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Event\PrepareApiAttributes;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;

class LoadSettingsFromDatabase
{
    
    protected $packagePrefix = 'davis-customheader.';

    protected $fieldsToGet = array(
    'customheader',
    'heightofheader',
    'jsofheader',
    'cssofheader'
    );
  
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings) {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events) {
        $events->listen(PrepareApiAttributes::class, [$this, 'prepareApiAttributes']);
    }

    public function prepareApiAttributes(PrepareApiAttributes $event) {
        if ($event->isSerializer(ForumSerializer::class)) {
            foreach ($this->fieldsToGet as $field) {
              $event->attributes[$this->packagePrefix . $field] = $this->settings->get($this->packagePrefix . $field);
            }
        }
    }
}
