Feature: As user
  In order to create composer configuration file from a template
  I should be able to use the command line

  Scenario: Full configuration
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create
    Then configuration file should contains:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <!-- https://phpunit.de/manual/current/en/appendixes.configuration.html -->
    <!-- BEGIN phpunit -->
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.5/phpunit.xsd"
      backupGlobals="true"
      backupStaticAttributes="false"
      bootstrap="/path/to/bootstrap.php"
      cacheTokens="false"
      colors="false"
      convertErrorsToExceptions="true"
      convertNoticesToExceptions="true"
      convertWarningsToExceptions="true"
      forceCoversAnnotation="false"
      mapTestClassNameToCoveredClassName="false"
      printerClass="PHPUnit_TextUI_ResultPrinter"
      printerFile="/path/to/ResultPrinter.php"
      processIsolation="false"
      stopOnError="false"
      stopOnFailure="false"
      stopOnIncomplete="false"
      stopOnSkipped="false"
      stopOnRisky="false"
      testSuiteLoaderClass="PHPUnit_Runner_StandardTestSuiteLoader"
      testSuiteLoaderFile="/path/to/StandardTestSuiteLoader.php"
      timeoutForSmallTests="1"
      timeoutForMediumTests="10"
      timeoutForLargeTests="60"
      verbose="false"
    >
    """
    And configuration file should contains:
    """
      <!-- BEGIN testsuites -->
      <testsuites><!-- testsuites -->
        <!-- BEGIN testsuite -->
        <testsuite name="My Test Suite"><!-- testsuite -->
          <directory>/path/to/*Test.php files</directory><!-- directory -->
          <file>/path/to/MyTest.php</file>
          <exclude>/path/to/exclude</exclude>
          <!-- testsuite2 -->
        </testsuite>
        <!-- END testsuite -->
        <!-- testsuites2 -->
        <!-- BEGIN testsuite2 -->
        <testsuite name="My Test Suite2">
          <directory suffix="Test.php" phpVersion="5.3.0" phpVersionOperator="&gt;=">/path/to/files</directory>
          <file phpVersion="5.3.0" phpVersionOperator="&gt;=">/path/to/MyTest.php</file>
        </testsuite>
        <!-- END testsuite2 -->
      </testsuites>
      <!-- END testsuites -->
    """
    And configuration file should contains:
    """
      <!-- BEGIN groups -->
      <groups><!-- groups -->
        <!-- BEGIN include -->
        <include><!-- include -->
          <group>name1</group><!-- group -->
          <group>name2</group>
          <!-- include2 -->
        </include>
        <!-- END include -->
        <!-- groups2 -->
        <!-- BEGIN exclude -->
        <exclude><!-- exclude -->
          <group>name3</group><!-- group -->
          <group>name4</group>
          <!-- exclude2 -->
        </exclude>
        <!-- END exclude -->
      </groups>
      <!-- END groups -->
    """
    And configuration file should contains:
    """
      <!-- BEGIN filter -->
      <filter><!-- filter -->
        <!-- BEGIN whitelist -->
        <whitelist><!-- whitelist -->
          <directory>src</directory><!-- directory -->
          <file>src.php</file>
          <!-- whitelist2 -->
          <!-- BEGIN exclude -->
          <exclude><!-- exclude -->
            <file>src/Infrastructure/SfApplication.php</file><!-- file -->
            <directory>src/Domain/Model</directory>
            <!-- exclude -->
          </exclude>
          <!-- END exclude -->
        </whitelist>
        <!-- END whitelist -->
        <!-- filter2 -->
      </filter>
      <!-- END filter -->
    """
    And configuration file should contains:
    """
      <!-- BEGIN logging -->
      <logging><!-- logging -->
        <log type="coverage-html" target="/tmp/report" lowUpperBound="35" highLowerBound="70"/><!-- log -->
        <log type="coverage-clover" target="/tmp/coverage.xml"/>
        <log type="coverage-php" target="/tmp/coverage.serialized"/>
        <!-- logging2 -->
        <log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
        <log type="junit" target="/tmp/logfile.xml" logIncompleteSkipped="false"/>
        <log type="testdox-html" target="/tmp/testdox.html"/>
        <log type="testdox-text" target="/tmp/testdox.txt"/>
      </logging>
      <!-- END logging -->
    """
    And configuration file should contains:
    """
      <!-- BEGIN listeners -->
      <listeners><!-- listeners -->
        <!-- BEGIN listener -->
        <listener class="MyListener" file="/optional/path/to/MyListener.php"><!-- listener -->
          <!-- BEGIN arguments -->
          <arguments><!-- arguments -->
            <array><!-- array -->
              <element key="0"><!-- element -->
                <string>Sebastian</string><!-- string -->
                <!-- element2 -->
              </element>
              <!-- array2 -->
            </array>
            <!-- arguments2 -->
            <integer>22</integer><!-- integer -->
            <string>April</string>
            <double>19.78</double>
            <null/>
            <object class="stdClass"/>
          </arguments>
          <!-- END arguments -->
          <!-- listener2 -->
        </listener>
        <!-- END listener -->
        <!-- listeners2 -->
      </listeners>
      <!-- END listeners -->
    """
    And configuration file should contains:
    """
      <!-- BEGIN php -->
      <php><!-- php -->
        <includePath>.</includePath><!-- includePath -->
        <ini name="foo" value="bar"/><!-- ini -->
        <const name="foo" value="bar"/>
        <!-- php2 -->
        <var name="foo" value="bar"/>
        <env name="foo" value="bar"/>
        <post name="foo" value="bar"/>
        <get name="foo" value="bar"/>
        <cookie name="foo" value="bar"/>
        <server name="foo" value="bar"/>
        <files name="foo" value="bar"/>
        <request name="foo" value="bar"/>
      </php>
      <!-- END php -->
    """
    And configuration file should contains:
    """
    </phpunit>
    <!-- END phpunit -->
    """

    @yo
  Scenario: Full configuration with added values
    Given I will use configuration template fixture "create_with_template.xml"
    And I have the folder "custom_folder"
    When I execute phpunitcm create with "custom_folder" and following options:
    """
    --config-attr "backupGlobals##false" --config-attr "test##./path" --test-suite-file "suite1##file11" --test-suite-file "My Test Suite##/path/to/MyTest.php##suffix##Plop.php" --test-suite-directory "suite1##directory11" --test-suite-directory "My Test Suite2##/path/to/files##phpVersion##7.0"  --test-suite-excluded "suite1##excluded11" --test-suite-excluded "My Test Suite##excluded21" --group-include group1 --group-exclude group2 --filter-whitelist-file path1 --filter-whitelist-directory path2 --filter-whitelist-excluded-file path3 --filter-whitelist-excluded-directory path4 --log "coverage-html##/tmp/report2##lowUpperBound##40" --listener "Class1" --listener "Class2##File2" --php "includePath##../" --php "ini##foo##plop" --php "ini##bar##foo"
    """
    Then configuration file at "custom_folder" should contains:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <!-- https://phpunit.de/manual/current/en/appendixes.configuration.html -->
    <!-- BEGIN phpunit -->
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.5/phpunit.xsd"
      backupGlobals="false"
      backupStaticAttributes="false"
      bootstrap="/path/to/bootstrap.php"
      cacheTokens="false"
      colors="false"
      convertErrorsToExceptions="true"
      convertNoticesToExceptions="true"
      convertWarningsToExceptions="true"
      forceCoversAnnotation="false"
      mapTestClassNameToCoveredClassName="false"
      printerClass="PHPUnit_TextUI_ResultPrinter"
      printerFile="/path/to/ResultPrinter.php"
      processIsolation="false"
      stopOnError="false"
      stopOnFailure="false"
      stopOnIncomplete="false"
      stopOnSkipped="false"
      stopOnRisky="false"
      testSuiteLoaderClass="PHPUnit_Runner_StandardTestSuiteLoader"
      testSuiteLoaderFile="/path/to/StandardTestSuiteLoader.php"
      timeoutForSmallTests="1"
      timeoutForMediumTests="10"
      timeoutForLargeTests="60"
      verbose="false"
      test="./path"
    >
    """
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN testsuites -->
      <testsuites><!-- testsuites -->
        <!-- BEGIN testsuite -->
        <testsuite name="My Test Suite"><!-- testsuite -->
          <directory>/path/to/*Test.php files</directory><!-- directory -->
          <file suffix="Plop.php">/path/to/MyTest.php</file>
          <exclude>/path/to/exclude</exclude>
          <!-- testsuite2 -->
          <exclude>excluded21</exclude>
        </testsuite>
        <!-- END testsuite -->
        <!-- testsuites2 -->
        <!-- BEGIN testsuite2 -->
        <testsuite name="My Test Suite2">
          <directory suffix="Test.php" phpVersion="7.0" phpVersionOperator="&gt;=">/path/to/files</directory>
          <file phpVersion="5.3.0" phpVersionOperator="&gt;=">/path/to/MyTest.php</file>
        </testsuite>
        <!-- END testsuite2 -->
        <testsuite name="suite1">
          <file>file11</file>
          <directory>directory11</directory>
          <exclude>excluded11</exclude>
        </testsuite>
      </testsuites>
      <!-- END testsuites -->
    """
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN groups -->
      <groups><!-- groups -->
        <!-- BEGIN include -->
        <include><!-- include -->
          <group>name1</group><!-- group -->
          <group>name2</group>
          <!-- include2 -->
          <group>group1</group>
        </include>
        <!-- END include -->
        <!-- groups2 -->
        <!-- BEGIN exclude -->
        <exclude><!-- exclude -->
          <group>name3</group><!-- group -->
          <group>name4</group>
          <!-- exclude2 -->
          <group>group2</group>
        </exclude>
        <!-- END exclude -->
      </groups>
      <!-- END groups -->
    """
# !!! <file>path3</file> and <directory>path4</directory> are not present in excluded node !!!!!!
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN filter -->
      <filter><!-- filter -->
        <!-- BEGIN whitelist -->
        <whitelist><!-- whitelist -->
          <directory>src</directory><!-- directory -->
          <file>src.php</file>
          <!-- whitelist2 -->
          <directory>path2</directory>
          <file>path1</file>
          <!-- BEGIN exclude -->
          <exclude><!-- exclude -->
            <file>src/Infrastructure/SfApplication.php</file><!-- file -->
            <directory>src/Domain/Model</directory>
            <!-- exclude -->
            <file>path3</file>
            <directory>path4</directory>
          </exclude>
          <!-- END exclude -->
        </whitelist>
        <!-- END whitelist -->
        <!-- filter2 -->
      </filter>
      <!-- END filter -->
    """
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN logging -->
      <logging><!-- logging -->
        <log type="coverage-html" target="/tmp/report2" lowUpperBound="40" highLowerBound="70"/><!-- log -->
        <log type="coverage-clover" target="/tmp/coverage.xml"/>
        <log type="coverage-php" target="/tmp/coverage.serialized"/>
        <!-- logging2 -->
        <log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
        <log type="junit" target="/tmp/logfile.xml" logIncompleteSkipped="false"/>
        <log type="testdox-html" target="/tmp/testdox.html"/>
        <log type="testdox-text" target="/tmp/testdox.txt"/>
      </logging>
      <!-- END logging -->
    """
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN listeners -->
      <listeners><!-- listeners -->
        <!-- BEGIN listener -->
        <listener class="MyListener" file="/optional/path/to/MyListener.php"><!-- listener -->
          <!-- BEGIN arguments -->
          <arguments><!-- arguments -->
            <array><!-- array -->
              <element key="0"><!-- element -->
                <string>Sebastian</string><!-- string -->
                <!-- element2 -->
              </element>
              <!-- array2 -->
            </array>
            <!-- arguments2 -->
            <integer>22</integer><!-- integer -->
            <string>April</string>
            <double>19.78</double>
            <null/>
            <object class="stdClass"/>
          </arguments>
          <!-- END arguments -->
          <!-- listener2 -->
        </listener>
        <!-- END listener -->
        <!-- listeners2 -->
        <listener class="Class1"/>
        <listener class="Class2" file="File2"/>
      </listeners>
      <!-- END listeners -->
    """
    And configuration file at "custom_folder" should contains:
    """
      <!-- BEGIN php -->
      <php><!-- php -->
        <includePath>../</includePath><!-- includePath -->
        <ini name="foo" value="plop"/><!-- ini -->
        <const name="foo" value="bar"/>
        <!-- php2 -->
        <var name="foo" value="bar"/>
        <env name="foo" value="bar"/>
        <post name="foo" value="bar"/>
        <get name="foo" value="bar"/>
        <cookie name="foo" value="bar"/>
        <server name="foo" value="bar"/>
        <files name="foo" value="bar"/>
        <request name="foo" value="bar"/>
        <ini name="bar" value="foo"/>
      </php>
      <!-- END php -->
    """