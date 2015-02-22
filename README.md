# Sentora Bouncer

Bouncer is a SentoraCP module to aid the 'lock down' a Sentora Server, it prevents access to the control panel to IP addresses that have not been added to it's whitelist or alternatively that are on it's blacklist.

![Screenshot of Bouncer Configuration](http://zppy.supared.com/bouncer_screenshot.png)

## License

This tool is released under the [GPL v2 license](LICENSE).

## Installation

You can install Bouncer by logging into your server and running the following commands:

Firstly, you need to add the Supared module repository (unless you already have it added):

```
zppy repo add zppy.supared.com
zppy update
```

Then install the package like so:

```
zppy install bouncer
```

Now that you have it install, go and activate the module in the Sentora Module Admin!