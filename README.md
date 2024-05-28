# AWS-CI/CD-Pipeline

This repository contains the code and instructions to set up a complete CI/CD pipeline using AWS CodeCommit, AWS CodeBuild, and AWS CodeDeploy for a project named "Museum". The pipeline automates the process of building, testing, and deploying the application to an EC2 instance.
## Architecture Diagram

![WhatsApp Image 2024-05-28 at 2 25 00 PM](https://github.com/abhijeetvyavhare/AWS-CICD-Pipeline/assets/94742219/813991dc-c8c7-46e1-a335-341b8895a502)

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Repository Setup](#repository-setup)
3. [CodeBuild Setup](#codebuild-setup)
4. [CodeDeploy Setup](#codedeploy-setup)
5. [Creating the Pipeline](#creating-the-pipeline)
6. [Connecting to EC2 Instance](#connecting-to-ec2-instance)

## Prerequisites

- AWS Account
- AWS CLI configured
- Basic understanding of AWS services (CodeCommit, CodeBuild, CodeDeploy, EC2)
- Git installed locally

## Repository Setup

1. **Create a Repository on AWS CodeCommit**
   - Create a new repository named `museum` on AWS CodeCommit.

2. **Clone the Repository**
   - Clone the repository using HTTPS:
     ```sh
     git clone https://git-codecommit.us-east-1.amazonaws.com/v1/repos/museum
     ```

3. **Set Up Project Directory**
   - Create a new directory for your project:
     ```sh
     mkdir museum
     cd museum
     ```

4. **Clone the Repository Locally**
   - Clone the repository into the project directory:
     ```sh
     git clone https://git-codecommit.us-east-1.amazonaws.com/v1/repos/museum .
     ```

5. **Add Application Code**
   - Paste your application code into the folder. Ensure it includes `appspec.yml` and `buildspec.yml` files.

6. **Initialize Git and Push to Repository**
   - Initialize git, add all files, commit, and push:
     ```sh
     git init
     git add .
     git commit -m "first commit"
     git push origin master
     ```

## CodeBuild Setup

1. **Create a Build Project**
   - Go to AWS CodeBuild and create a new project.

2. **Configure Source**
   - Source provider: CodeCommit
   - Repository: museum
   - Branch: master

3. **Configure Environment**
   - OS: Ubuntu
   - Runtime: Managed image
   - Image: aws/codebuild/standard:5.0
   - Service role: Create a new service role

4. **Configure Buildspec**
   - Ensure your `buildspec.yml` is in the root of the repository.

5. **Configure Artifacts**
   - Type: Amazon S3
   - Bucket name: (Create a new S3 bucket and provide the name)
   - Packaging: ZIP

6. **Create and Start Build**
   - Create the build project and start the build to verify the configuration.

## CodeDeploy Setup

1. **Launch EC2 Instance**
   - Launch an Ubuntu EC2 instance.
   - Configure security group to allow necessary traffic.
   - Add the following user data script:
     ```sh
     #!/bin/bash

     sudo apt update
     sudo apt install apache2 php libapache2-mod-php php-mysql php-dom php-gd mysql-server git -y

     sudo systemctl start apache2
     sudo systemctl enable apache2
     sudo systemctl start mysql
     sudo systemctl enable mysql

     sudo mkdir /var/www/html/museum
     sudo apt update -y  # Update package repositories (for Amazon Linux)
     sudo apt install -y ruby  # Install Ruby (required for CodeDeploy agent)
     sudo apt install -y wget  # Install wget (required for downloading CodeDeploy agent)
     sudo wget https://aws-codedeploy-us-east-1.s3.us-east-1.amazonaws.com/latest/install
     sudo chmod +x ./install
     sudo ./install auto
     sudo service codedeploy-agent start
     sudo service codedeploy-agent status
     ```

2. **Create an IAM Role for EC2**
   - Role name: Museum-role
   - Use case: EC2
   - Attach policies:
     - AmazonEC2FullAccess
     - AmazonEC2RoleforAWSCodeDeploy
     - AmazonS3FullAccess
     - AWSCodeDeployRole

3. **Attach IAM Role to EC2 Instance**
   - Attach the `Museum-role` to the EC2 instance.

## Creating the Pipeline

1. **Create a Pipeline**
   - Go to AWS CodePipeline and create a new pipeline.
   - Execution mode: Queued
   - Service role: Create a new service role

2. **Configure Source Stage**
   - Source provider: CodeCommit
   - Repository: museum
   - Branch: master

3. **Configure Build Stage**
   - Build provider: AWS CodeBuild
   - Project: Select the previously created CodeBuild project
   - Build type: Single build

4. **Configure Deploy Stage**
   - Deploy provider: AWS CodeDeploy
   - Application: Create and select the new CodeDeploy application
   - Deployment group: Create and select the new deployment group

5. **Create the Pipeline**
   - Complete the pipeline creation and start the execution to verify the setup.

## Connecting to EC2 Instance

1. **Connect to EC2 Instance**
   - SSH into the EC2 instance:
     ```sh
     ssh -i "your-key-pair.pem" ubuntu@your-ec2-public-dns
     ```

2. **Create Project Directory**
   - Ensure the project directory is created in `/var/www/html`:
     ```sh
     sudo mkdir -p /var/www/html/museum
     ```

By following these steps, you will have a complete CI/CD pipeline set up on AWS for the Museum application, automating the process from code commit to deployment on an EC2 instance.
