#!/bin/bash -x
# Password: AKIAJFNYLNUJRG33PHHA
# List of Domains:
# 1. https://redshift.local.dc.staging.triophase.com/             => Application
# 2. https://images.redshift.local.dc.staging.triophase.com/      => Docker Image Repository


sshpass -p 'AKIAJFNYLNUJRG33PHHA' ssh -R 443:localhost:443 176.58.126.195