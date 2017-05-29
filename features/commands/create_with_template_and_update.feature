Feature: As user
  In order to create composer configuration file from a template, override some value and or add new ones
  I should be able to use the command line

  Scenario: Update some templates attributes and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --config-attr "backupGlobals##false"
    --config-attr "test##./path"
    """
    Then configuration file should contains:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <!-- https://phpunit.de/manual/current/en/appendixes.configuration.html -->
    <!-- BLOCK_COMMENT - BEGIN phpunit -->
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.5/phpunit.xsd"
      backupGlobals="false"
      bootstrap="/path/to/bootstrap.php"
      printerClass="PHPUnit_TextUI_ResultPrinter"
      printerFile="/path/to/ResultPrinter.php"
      timeoutForLargeTests="60"
      unknowAttribute="true"
      test="./path"
    >
    """

  Scenario: Update some template test suite items and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --test-suite-file "suite1##file11"
    --test-suite-file "My Test Suite##/path/to/MyTest.php##suffix##Plop.php"
    --test-suite-directory "suite1##directory11"
    --test-suite-directory "My Test Suite2##/path/to/dir##phpVersion##7.0"
    --test-suite-excluded "suite1##excluded11"
    --test-suite-excluded "My Test Suite##excluded21"
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN testsuites -->
      <testsuites><!-- INLINE_COMMENT - testsuites -->
        <!-- BLOCK_COMMENT - BEGIN testsuite -->
        <testsuite name="My Test Suite"><!-- INLINE_COMMENT - testsuite -->
          <directory>/path/to/*Test.php files</directory><!-- INLINE_COMMENT - directory -->
          <file suffix="Plop.php">/path/to/MyTest.php</file>
          <exclude>/path/to/exclude</exclude>
          <!-- INLINE_COMMENT_ALONE - testsuite -->
          <exclude>excluded21</exclude>
        </testsuite>
        <!-- BLOCK_COMMENT - END testsuite -->
        <!-- INLINE_COMMENT_ALONE - testsuites -->
        <!-- BLOCK_COMMENT - BEGIN testsuite2 -->
        <testsuite name="My Test Suite2">
          <directory suffix="Test.php" phpVersion="7.0" phpVersionOperator="&gt;=">/path/to/dir</directory>
          <file phpVersion="5.3.0" phpVersionOperator="&gt;=">/path/to/MyTest.php</file>
        </testsuite>
        <!-- BLOCK_COMMENT - END testsuite2 -->
        <testsuite name="suite1">
          <file>file11</file>
          <directory>directory11</directory>
          <exclude>excluded11</exclude>
        </testsuite>
      </testsuites>
      <!-- BLOCK_COMMENT - END testsuites -->
    """

  Scenario: Update some templates groups item and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --group-include group1
    --group-exclude group2
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN groups -->
      <groups><!-- INLINE_COMMENT - groups -->
        <!-- BLOCK_COMMENT - BEGIN include -->
        <include><!-- INLINE_COMMENT - include -->
          <group>name1</group><!-- INLINE_COMMENT - group -->
          <group>name2</group>
          <!-- INLINE_COMMENT_ALONE - include -->
          <group>group1</group>
        </include>
        <!-- BLOCK_COMMENT - END include -->
        <!-- INLINE_COMMENT_ALONE - groups -->
        <!-- BLOCK_COMMENT - BEGIN exclude -->
        <exclude><!-- INLINE_COMMENT - exclude -->
          <group>name3</group><!-- INLINE_COMMENT - group -->
          <group>name4</group>
          <!-- INLINE_COMMENT_ALONE - exclude -->
          <group>group2</group>
        </exclude>
        <!-- BLOCK_COMMENT - END exclude -->
      </groups>
      <!-- BLOCK_COMMENT - END groups -->
    """

  Scenario: Update some templates filter items and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --filter-whitelist-file path1
    --filter-whitelist-directory path2
    --filter-whitelist-excluded-file path3
    --filter-whitelist-excluded-directory path4
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN filter -->
      <filter><!-- INLINE_COMMENT - filter -->
        <!-- BLOCK_COMMENT - BEGIN whitelist -->
        <whitelist><!-- INLINE_COMMENT - whitelist -->
          <directory>src</directory><!-- INLINE_COMMENT - directory -->
          <file>src.php</file>
          <!-- INLINE_COMMENT_ALONE - whitelist -->
          <file>path1</file>
          <directory>path2</directory>
          <!-- BLOCK_COMMENT - BEGIN exclude -->
          <exclude><!-- INLINE_COMMENT - exclude -->
            <file>path/to/file.php</file><!-- INLINE_COMMENT - file -->
            <directory>path/to/dir</directory>
            <!-- INLINE_COMMENT_ALONE - exclude -->
            <file>path3</file>
            <directory>path4</directory>
          </exclude>
          <!-- BLOCK_COMMENT - END exclude -->
        </whitelist>
        <!-- BLOCK_COMMENT - END whitelist -->
        <!-- INLINE_COMMENT_ALONE - filter -->
      </filter>
      <!-- BLOCK_COMMENT - END filter -->
    """

  Scenario: Update some templates logging items and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --log "coverage-html##/tmp/report2##lowUpperBound##40"
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN logging -->
      <logging><!-- INLINE_COMMENT - logging -->
        <log type="coverage-html" target="/tmp/report2" lowUpperBound="40" highLowerBound="70"/><!-- INLINE_COMMENT - log -->
        <!-- INLINE_COMMENT_ALONE - logging -->
        <log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
        <log type="testdox-html" target="/tmp/testdox.html"/>
      </logging>
      <!-- BLOCK_COMMENT - END logging -->
    """

  Scenario: Update some templates listeners items and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --listener "Class1"
    --listener "Class2##File2"
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN listeners -->
      <listeners><!-- INLINE_COMMENT - listeners -->
        <!-- BLOCK_COMMENT - BEGIN listener -->
        <listener class="MyListener" file="/optional/path/to/MyListener.php"><!-- INLINE_COMMENT - listener -->
          <!-- BLOCK_COMMENT - BEGIN arguments -->
          <arguments><!-- INLINE_COMMENT - arguments -->
            <array><!-- INLINE_COMMENT - array -->
              <element key="0"><!-- INLINE_COMMENT - element -->
                <string>Sebastian</string><!-- INLINE_COMMENT - string -->
                <!-- INLINE_COMMENT_ALONE - element -->
              </element>
              <!-- INLINE_COMMENT_ALONE - array -->
            </array>
            <!-- INLINE_COMMENT_ALONE - arguments -->
            <integer>22</integer><!-- INLINE_COMMENT - integer -->
            <string>April</string>
            <double>19.78</double>
            <null/>
            <object class="stdClass"/>
          </arguments>
          <!-- BLOCK_COMMENT - END arguments -->
          <!-- INLINE_COMMENT_ALONE - listener -->
        </listener>
        <!-- BLOCK_COMMENT - END listener -->
        <!-- INLINE_COMMENT_ALONE - listeners -->
        <listener class="Class1"/>
        <listener class="Class2" file="File2"/>
      </listeners>
      <!-- BLOCK_COMMENT - END listeners -->
    """

  Scenario: Update some templates php items and add new ones
    Given I will use configuration template fixture "create_with_template.xml"
    When I execute phpunitcm create with following options:
    """
    --php "includePath##../"
    --php "ini##foo##plop"
    --php "ini##bar##foo"
    """
    Then configuration file should contains:
    """
      <!-- BLOCK_COMMENT - BEGIN php -->
      <php><!-- INLINE_COMMENT - php -->
        <includePath>../</includePath><!-- INLINE_COMMENT - includePath -->
        <ini name="foo" value="plop"/><!-- INLINE_COMMENT - ini -->
        <!-- INLINE_COMMENT_ALONE - php -->
        <const name="foo" value="bar"/>
        <ini name="bar" value="foo"/>
      </php>
      <!-- BLOCK_COMMENT - END php -->
    """
