<?php declare(strict_types = 1);

namespace Netmosfera\AmpChrome\Process;

class DevSettings
{
    public static function get(){
        // see https://peter.sh/experiments/chromium-command-line-switches/

        $settings["remote-debugging-port"] = 0;

        // Background networking is probably some Google services
        $settings["disable-background-networking"] = NULL;

        // [???] Hidden pages are slowed down by default in chrome
        $settings["disable-background-timer-throttling"] = NULL;

        // [???] Suppresses hang monitor dialogs in renderer processes.
        $settings["disable-hang-monitor"] = NULL;

        // MISC options
        $settings["disable-client-side-phishing-detection"] = NULL;
        $settings["disable-popup-blocking"] = NULL;
        $settings["disable-default-apps"] = NULL;
        $settings["disable-translate"] = NULL;
        $settings["no-first-run"] = NULL;
        $settings["mute-audio"] = NULL;

        $settings["disable-prompt-on-repost"] = NULL;
        $settings["disable-sync"] = NULL;
        $settings["metrics-recording-only"] = NULL;
        $settings["safebrowsing-disable-auto-update"] = NULL;
        $settings["enable-automation"] = NULL;
        $settings["password-store=basic"] = NULL;

        $settings["window-size"] = "1024,768";

        return $settings;
    }
}
