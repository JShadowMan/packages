#!/usr/bin/env python
#
# Copyright (C) 2017 DL
#
from setuptools import setup, find_packages

setup(
    name='error_logger',
    version='0.0.1',
    description='error logger for linux',

    packages=find_packages('.'),

    include_package_data=True,
    entry_points={
        'console_scripts': [
            'error_logger_server = error_logger.shell:entry_pointer'
        ],
    }
)
