# Dropper

- For unknown reasons our BannerLMB instance is not processing drops for some terms. _(probably some user input difference we can't find yet)_
- So, for now I wrote this command line tool to take a `.csv` and output the `xml` file needed to send to Banner to undo the LMB enrollments that should be dropped

## csv should look like this

```csv
courseid,studentid
1234.201701,99123456
8181.201701,99411111
```

## hard-code your params in the file

```php
$sourcedata = "exampledata.csv";
$destination = "results/thedrops.xml";
$termkey = '201801';
$thisdate = '2017-06-16T11:05:33';
```

## run it
```bash
php dropper
```

## add the doctype to the second line of the newly created xml
```xml
<!DOCTYPE enterprise SYSTEM "ldisp-2.0.dtd">
```

## zap to server
