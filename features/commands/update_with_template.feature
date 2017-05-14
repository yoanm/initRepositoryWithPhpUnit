Feature: As user
  In order to update composer configuration file from a template
  I should be able to use the "update" command line

  Scenario: Full configuration file
    Given I will use configuration template at "./template/key_order.json" with:
    """
    {
      "require": {},
      "scripts": {},
      "name": null,
      "bin": [],
      "support": {},
      "description": null,
      "authors": [],
      "require-dev": {},
      "provide": {},
      "autoload-dev": {},
      "suggest": {},
      "version": null,
      "autoload": {},
      "type": null,
      "unmanaged_a": null,
      "unmanaged_b": {},
      "unmanaged_c": null,
      "unmanaged_d": {}
    }

    """
    And I will use configuration template at "./template/template.json" with:
    """
    {
      "name": "default-template-name",
      "type": "default-template-type",
      "license": "default-template-license",
      "version": "default-template-version",
      "description": "default-template-description",
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2"
      ],
      "authors": [
        {
          "name": "default-template-name1",
          "email": "default-template-email1",
          "role": "default-template-role1"
        },
        {
          "name": "default-template-name2",
          "email": "default-template-email2",
          "role": "default-template-role2"
        }
      ],
      "provide": {
        "template-package1": "default-template-provided-package1",
        "template-package2": "default-template-provided-package2"
      },
      "suggest": {
        "template-package1": "default-template-suggested-package1",
        "template-package2": "default-template-suggested-package2"
      },
      "support": {
        "template-type1": "default-template-support-type1",
        "template-type2": "default-template-support-type2"
      },
      "autoload": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "autoload-dev": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "require": {
        "template-requirement1": "default-template-required-package1"
      },
      "require-dev": {
        "template-requirement1": "default-template-required-dev-package1"
      },
      "scripts": {
        "default-template-script-1": [
          "default-template-script1-command_1",
          "default-template-script1-command_2"
        ],
        "default-template-script-2": [
          "default-template-script2-command_1",
          "default-template-script2-command_2"
        ]
      },
      "unmanaged_d": ["D"],
      "unmanaged_a": "A"
    }

    """
    When I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3 --keyword my_keyword --keyword my_keyword2 --author author#email#role --author name2 --author name3#email3 --provide name/A#url1 --provide name2/B#url2 --provide name/C#url3 --suggest "name/A#description 1" --suggest "name2/B#description 2" --suggest "name/C#description 3" --support "typeA#urlA" --support "typeB#urlB" --support "typeC#urlC" --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "\\vendor2\\Test\\#src2" --autoload-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2" --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2" --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file key order should be:
    """
    [
      "require",
      "scripts",
      "name",
      "bin",
      "support",
      "description",
      "authors",
      "require-dev",
      "provide",
      "autoload-dev",
      "suggest",
      "version",
      "autoload",
      "type",
      "unmanaged_a",
      "unmanaged_d",
      "license",
      "keywords"
    ]
    """
    And configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """
    And configuration file should contains:
    """
    {
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2",
        "DEFAULT-KEYWORD1",
        "DEFAULT-KEYWORD2",
        "my_keyword",
        "my_keyword2"
      ]
    }
    """
    And configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-template-name1",
          "email": "default-template-email1",
          "role": "default-template-role1"
        },
        {
          "name": "default-template-name2",
          "email": "default-template-email2",
          "role": "default-template-role2"
        },
        {
          "name": "default-name1",
          "email": "default-email1",
          "role": "default-role1"
        },
        {
          "name": "default-name2",
          "email": "default-email2",
          "role": "default-role2"
        },
        {
          "name": "author",
          "email": "email",
          "role": "role"
        },
        {
          "name": "name2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """
    And configuration file should contains:
    """
    {
      "provide": {
        "template-package1": "default-template-provided-package1",
        "template-package2": "default-template-provided-package2",
        "package1": "default-provided-package1",
        "package2": "default-provided-package2",
        "name/A": "url1",
        "name2/B": "url2",
        "name/C": "url3"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "suggest": {
        "template-package1": "default-template-suggested-package1",
        "template-package2": "default-template-suggested-package2",
        "package1": "default-suggested-package1",
        "package2": "default-suggested-package2",
        "name/A": "description 1",
        "name2/B": "description 2",
        "name/C": "description 3"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "support": {
        "template-type1": "default-template-support-type1",
        "template-type2": "default-template-support-type2",
        "type1": "default-support-type1",
        "type2": "default-support-type2",
        "typeA": "urlA",
        "typeB": "urlB",
        "typeC": "urlC"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
         "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
         "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "\\vendor2\\Test\\": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require": {
        "template-requirement1": "default-template-required-package1",
        "requirement1": "default-required-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require-dev": {
        "template-requirement1": "default-template-required-dev-package1",
        "requirement1": "default-required-dev-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "scripts": {
        "default-template-script-1": [
          "default-template-script1-command_1",
          "default-template-script1-command_2"
        ],
        "default-template-script-2": [
          "default-template-script2-command_1",
          "default-template-script2-command_2"
        ],
        "default-script-1": [
            "default-script1-command_1",
            "default-script1-command_2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name1": [
          "command1",
          "command2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """
    And configuration file should contains:
    """
    {
      "unmanaged_a": "A",
      "unmanaged_d": ["D"]
    }
    """

  Scenario: Full configuration file with values to update (from old file and from template)
    Given I will use configuration template at "./template/template.json" with:
    """
    {
      "name": "default-template-name",
      "type": "default-template-type",
      "license": "default-template-license",
      "version": "default-template-version",
      "description": "default-template-description",
      "keywords": [
        "DEFAULT-TEMPLATE-KEYWORD1",
        "DEFAULT-TEMPLATE-KEYWORD2"
      ],
      "authors": [
        {
          "name": "default-template-name1",
          "email": "default-template-email1",
          "role": "default-template-role1"
        },
        {
          "name": "default-template-name2",
          "email": "default-template-email2",
          "role": "default-template-role2"
        }
      ],
      "provide": {
        "template-package1": "default-template-provided-package1",
        "template-package2": "default-template-provided-package2"
      },
      "suggest": {
        "template-package1": "default-template-suggested-package1",
        "template-package2": "default-template-suggested-package2"
      },
      "support": {
        "template-type1": "default-template-support-type1",
        "template-type2": "default-template-support-type2"
      },
      "autoload": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "autoload-dev": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "default-template-psr0-path1",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "default-template-psr4-path1",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2"
        }
      },
      "require": {
        "template-requirement1": "default-template-required-package1"
      },
      "require-dev": {
        "template-requirement1": "default-template-required-dev-package1"
      },
      "scripts": {
        "default-template-script-1": [
          "default-template-script1-command_1",
          "default-template-script1-command_2"
        ],
        "default-template-script-2": [
          "default-template-script2-command_1",
          "default-template-script2-command_2"
        ]
      }
    }

    """
    When I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3  --author default-template-name1#email#role --author default-name2#email2 --author name3#email3 --provide template-package1#url0 --provide package1#url1 --provide name2/B#url2 --suggest "package1#description 1" --suggest "name2/B#description 2" --suggest "template-package1#description 0" --support "type1#url1" --support "typeA#urlA" --support "template-type1#url0" --autoload-psr0 "DefaultNamespace\\DefaultSubNamespace#src1" --autoload-psr4 "\\DefaultNamespace\\DefaultSubNamespace2\\#src2" --autoload-psr4 "\\vendor2\\Test\\#src4" --autoload-psr0 "vendor1\\Test2#src3" --autoload-psr0 "DefaultTemplateNamespace\\\\DefaultSubNamespace#src0" --autoload-psr4 "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\#src0" --autoload-dev-psr0 "DefaultNamespace\\DefaultSubNamespace2#src1" --autoload-dev-psr4 "\\DefaultNamespace\\DefaultSubNamespace\\#src2" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "DefaultTemplateNamespace\\\\DefaultSubNamespace#src0" --autoload-dev-psr4 "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\#src0" --require "requirement1#custom" --require "vendor2/B#>=2.0.0" --require "template-requirement1#custom0" --require-dev "requirement1#custom" --require-dev "vendor2/B#>=2.0.0" --require-dev "template-requirement1#custom0" --script "default-script-1#command1" --script "name2#command1" --script "default-script-1#command2" --script "default-template-script-1#command_0"
    """
    Then configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """
    And configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-template-name1",
          "email": "email",
          "role": "role"
        },
        {
          "name": "default-template-name2",
          "email": "default-template-email2",
          "role": "default-template-role2"
        },
        {
          "name": "default-name1",
          "email": "default-email1",
          "role": "default-role1"
        },
        {
          "name": "default-name2",
          "email": "email2",
          "role": "default-role2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """
    And configuration file should contains:
    """
    {
      "provide": {
        "template-package1": "url0",
        "template-package2": "default-template-provided-package2",
        "package1": "url1",
        "package2": "default-provided-package2",
        "name2/B": "url2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "suggest": {
        "template-package1": "description 0",
        "template-package2": "default-template-suggested-package2",
        "package1": "description 1",
        "package2": "default-suggested-package2",
        "name2/B": "description 2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "support": {
        "template-type1": "url0",
        "template-type2": "default-template-support-type2",
        "type1": "url1",
        "type2": "default-support-type2",
        "typeA": "urlA"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultTemplateNamespace\\\\DefaultSubNamespace": "src0",
         "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
         "DefaultNamespace\\DefaultSubNamespace": "src1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
         "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "src0",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "src2",
          "\\vendor2\\Test\\": "src4"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultTemplateNamespace\\\\DefaultSubNamespace": "src0",
          "DefaultTemplateNamespace\\\\DefaultSubNamespace2": "default-template-psr0-path2",
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace\\\\": "src0",
          "\\\\DefaultTemplateNamespace\\\\DefaultSubNamespace2\\\\": "default-template-psr4-path2",
          "\\DefaultNamespace\\DefaultSubNamespace\\": "src2",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require": {
        "template-requirement1": "custom0",
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require-dev": {
        "template-requirement1": "custom0",
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "scripts": {
        "default-template-script-1": [
          "command_0"
        ],
        "default-template-script-2": [
          "default-template-script2-command_1",
          "default-template-script2-command_2"
        ],
        "default-script-1": [
            "command1",
            "command2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """