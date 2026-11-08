# COLORS Lab (for COP 4331C)

Colors web application based off of the assignment specifications. It consists of a basic login page alongside a main page which allows users to input color names and upload them to their profile, then be able to search for them via the search box.

To use the app, visit my website http://steffpcop4331.com or follow the setup instructions to use it on a remote host.

The program was built within a LAMP stack droplet on a Apache environment hosted on DigitalOcean alongside an instance of MySQL running. 

The setup process can be done via an ssh client such as Putty (www.chiark.greenend.org.uk/~sgtatham/putty/latest.html), though ssh file management programs such as Filezilla (filezilla-project.org/download.php?type=client#close) are also helpful.
(Note: Any instance of "placeholder" in the instructions can be replaced with any name/credentials.)

1. Register a LAMP droplet on DigitalOcean via https://marketplace.digitalocean.com/apps/lamp, and register a root password within the creation page.
   
2. Log on to the root with the credentials via the DigitalOcean web console or Putty. Then, use Filezilla to find the MySQL password (Directory location C:/root/).
   
3. Use command 
```bash
mysql -u root -p 
```
and log on with the password. Then, proceed to use 

```mysql
create database COP4331;
use COP4331;
```
And then create the login and color tables via 
```mysql
CREATE TABLE `COP4331`.`Users` ( `ID` INT NOT NULL AUTO_INCREMENT , `FirstName` VARCHAR(50) NOT NULL DEFAULT '' , `LastName` VARCHAR(50) NOT NULL DEFAULT '' , `Login` VARCHAR(50) NOT NULL DEFAULT '' , `Password` VARCHAR(50) NOT NULL DEFAULT '' , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

insert into Users (FirstName,LastName,Login,Password) VALUES ('placeholder','placeholder','placeholder','placeholder');
```

```mysql
CREATE TABLE `COP4331`.`Colors` ( `ID` INT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(50) NOT NULL DEFAULT '' , `UserID` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

insert into Colors (Name,UserID) VALUES ('placeholder',1);
```
The insert functions can use any data within the same format.


4. Run the following command 
```mysql
create user 'placeholder' identified by 'placeholder';
grant all privileges on COP4331.* to 'placeholder'@'%';
```
in order to create a user for the PHP files to access.

5. With Filezilla, insert the contents of this repository into the server root directory (C:/var/www/html)
   
6. Use a Javascript enabled web browser to access the website using input information.

To use the application, use the username and password registered within the database to access the main menu. Then, enter the string you wish to search for into the search box for results to show. To add a color, write out the full color name and use the Add button. The new color will be accessible immediately via search.

AI Disclosure:
For this assignment, Github Copilot was used to explain the causation of some errors and to directly apply corresponding changes. On one instance, Copilot was allowed to directly commit to the repo. 

The following chatlogs:

"    b9765961-31cd-5145-b95b-75ac60f7d870

Explain why this job failed.
Assistant response
Thinking

The Super-linter step failed because PHP style and static-analysis checks found errors—not because of a flaky runner. PHPCS reported tabs, incorrect indentation, trailing whitespace, and a closing PHP tag; PHPStan flagged “Undefined variable: $obj” and “Undefined variable: $err” in AddColor.php.

Psalm also reported issues across AddColor.php, Login.php, and SearchColors.php, including risky truthiness checks and an unrecognized mysqli_stmt class. Likely fix: correct the PHP formatting and resolve the reported type/variable issues (including Psalm’s mysqli configuration); I can work out the full fix.
Conversation turn workingUser message

    b9765961-31cd-5145-b95b-75ac60f7d870

If you could, please do work out the full fix to meet the linter specifications.
Assistant response

I’ll inspect the failed commit’s PHP files and linter configuration, then make the smallest fixes that satisfy the configured checks.
Thinking
Thinking
Thinking

The lint failures come from PHP formatting plus broken JSON response helpers and request-body typing. I’m correcting those across all three API endpoints, then I’ll run the repository’s Super-Linter checks to catch any remaining configuration or static-analysis issues.
Thinking
Thinking
Thinking

The PHP checks now pass once Super-Linter is pointed at the repository’s root Psalm configuration; without that, it ignores the existing config and treats the missing mysqli extension types as errors. I’m making that config-path explicit in the workflow and will verify the final run against the branch’s changed files."

"    22e3d471-9666-5b36-bef2-94e2ceb76577

Explain why this job failed.
Assistant response
Thinking

The Super-linter step failed on PHP checks: PHPCS reported 127 style violations, PHPStan found returnWithInfo called with too few arguments in API/SearchColors.php:39, and Psalm reported 41 errors across the PHP files. The key error is: “Function returnWithInfo invoked with 1 parameter, 3 required.” This points to code and lint/style issues, not a flaky runner.

Likely fix: correct the PHP call and address the reported formatting and static-analysis issues; I can work out the full fix.
Conversation turn completeUser message

    22e3d471-9666-5b36-bef2-94e2ceb76577

Could you suggest me as to how to fix the file to meet all the linter requirements?
Assistant response
Thinking
Thinking

For API/SearchColors.php, fix the issues in this order:

    Resolve the returnWithInfo() mismatch. The call at line 39 passes one argument, but PHPStan sees the three-argument version from Login.php. These endpoint files define identically named global helpers, which confuses analysis across the project. Give the SearchColors helper and call a unique name, such as returnWithSearchResults($searchResults).
    Format the file to the PHPCS rules. It currently mixes tabs and spaces and has spacing, brace, trailing-whitespace, and closing-tag violations. Running phpcbf on the file should automatically fix many of the 127 reported style issues; remove the final ?> and ensure there’s a newline at EOF.
    Address the Psalm errors in this file. Use strict checks for missing/empty input, check that file_get_contents() succeeded before json_decode(), and add parameter and return types to the helper functions. Psalm also reports mysqli_stmt as undefined; configure Psalm to recognize the mysqli extension rather than suppressing those errors.

The run also reports failures in Login.php and AddColor.php, so fixing only SearchColors may not make the entire lint job pass. I can make the changes and verify them if you’d like.
Conversation turn completeUser message

Please show me the changes you'd make within the files for me to verify myself before commiting.
Assistant response
Thinking
Thinking
Thinking
Thinking

I haven’t changed or committed anything. Here are the targeted edits I’d review first; they address the errors reported for these three PHP files.

API/SearchColors.php — validate the request, collect results as an array, and give this endpoint’s response helper a distinct name. That fixes PHPStan’s one-argument/three-argument returnWithInfo() conflict and avoids manually assembling JSON.
PHP

$inData = getRequestInfo();

if (
    !isset($inData['search'], $inData['userId'])
    || !is_string($inData['search'])
    || (!is_string($inData['userId']) && !is_int($inData['userId']))
) {
    returnWithError('Invalid search request');
    exit;
}

$searchResults = [];

// ... after executing the query:
while ($row = $result->fetch_assoc()) {
    $searchResults[] = (string) $row['Name'];
}

if ($searchResults === []) {
    returnWithError('No Records Found');
} else {
    returnWithSearchInfo($searchResults);
}

Replace the helpers at the bottom with typed versions. getRequestInfo() checks for a failed read before calling json_decode(); the other helpers use JSON encoding rather than string concatenation.
PHP

/** @return array<string, mixed> */
function getRequestInfo(): array
{
    $input = file_get_contents('php://input');

    if ($input === false) {
        return [];
    }

    $data = json_decode($input, true);

    return is_array($data) ? $data : [];
}

function sendResultInfoAsJson(string $json): void
{
    header('Content-type: application/json');
    echo $json;
}

function returnWithError(string $error): void
{
    sendResultInfoAsJson(json_encode(
        ['id' => 0, 'firstName' => '', 'lastName' => '', 'error' => $error],
        JSON_THROW_ON_ERROR
    ));
}

function returnWithSearchInfo(array $searchResults): void
{
    sendResultInfoAsJson(json_encode(
        ['results' => $searchResults, 'error' => ''],
        JSON_THROW_ON_ERROR
    ));
}

API/Login.php — remove the unused initial variables and reject missing or non-string credentials before using them.
PHP

$inData = getRequestInfo();

if (
    !isset($inData['login'], $inData['password'])
    || !is_string($inData['login'])
    || !is_string($inData['password'])
) {
    returnWithError('Invalid login request');
    exit;
}

Also type its helpers: getRequestInfo(): array, sendResultInfoAsJson(string $json): void, returnWithError(string $error): void, and returnWithInfo(string $firstName, string $lastName, int $id): void. In the login result, cast the database ID to an integer when calling the helper: returnWithInfo((string) $row['firstName'], (string) $row['lastName'], (int) $row['ID']);.

API/AddColor.php — check the request values explicitly, which addresses Psalm’s risky truthiness check.
PHP

$inData = getRequestInfo();

if (
    !isset($inData['color'], $inData['userId'])
    || !is_string($inData['color'])
    || (!is_string($inData['userId']) && !is_int($inData['userId']))
) {
    returnWithError('Invalid color request');
    exit;
}

$color = $inData['color'];
$userId = (string) $inData['userId'];

Give its helpers the same explicit signatures as Login’s (getRequestInfo(): array, sendResultInfoAsJson(string $json): void, returnWithError(string $error): void).

The mysqli_stmt Psalm errors aren’t fixed by edits to these files: Psalm’s environment needs to recognize the mysqli extension, either by enabling/installing it for the lint run or configuring Psalm with the appropriate stubs. For PHPCS’s formatting findings, the run says PHPCBF can automatically fix the reported violations; I’d run it on the affected PHP files, then review its formatting changes before committing.
Conversation turn completeUser message

Could you explain to me how to run PHPCBF?
Assistant response

PHPCBF (PHP Code Beautifier and Fixer) ships with PHP_CodeSniffer and auto-fixes the style violations PHPCS reports. Super-linter uses the PSR-12 standard by default, so run it with that standard. Check your repo's .github/linters/phpcs.xml in case it sets a different one.

1. Install PHP_CodeSniffer (needs PHP and Composer):
bash

composer global require squizlabs/php_codesniffer

Then make sure Composer's global bin directory is on your PATH. Find it with composer global config bin-dir --absolute.

2. See what would change. phpcs reports violations and changes nothing:
bash

phpcs --standard=PSR12 API/

3. Fix them automatically:
bash

phpcbf --standard=PSR12 API/

You can also target a single file, for example phpcbf --standard=PSR12 API/SearchColors.php.

4. Review and re-check:
bash

git diff
phpcs --standard=PSR12 API/

Notes:

    Run PHPCBF from the repo root, and commit or stash your work first so git diff shows only its changes.
    PHPCBF fixes formatting only: tabs, spacing, trailing whitespace, the closing ?> tag, and the final newline. It won't fix the PHPStan or Psalm errors, which need the code edits I described earlier.
    If you'd rather not install PHP locally, you can run Super-linter locally with Docker. This mirrors CI exactly:

bash

docker run --rm -e RUN_LOCAL=true -e VALIDATE_PHP_PHPCS=true -e FIX_PHP_PHPCBF=true -v "$PWD":/tmp/lint ghcr.io/super-linter/super-linter:v9.0.0

Setting FIX_PHP_PHPCBF=true makes it apply the fixes to your files. You can also set FIX_PHP_PHPCBF in the workflow, but then the job needs permission to commit the fixes back."

Limitations:
The program was only tested on a Apache environment hosted on DigitalOcean webservices, there is potential failure in other environments. HTML file may fail to run on Javascript unenabled browsers. Program was tested primarily on Google Chrome. User is assumed to be using the given said environments whilst deploying the application.
