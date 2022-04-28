
# Snowtricks - Project 6 from PHP/Symfony developper route on OpenClassrooms

A CRUD-like project, where users can register and look snowboard tricks up. Users can also comment snowboard tricks.


## Installation

To install the project locally, you need to have Docker and Docker-Compose installed locally. Then simply run these commands:

```bash
  git clone git@github.com:AlexandreGerault/snowtricks.git
  cd snowtricks
  make install
```

You are now ready to visit the website on http://localhost:3333 by default. You can change settings in the `.env`, at the root of the project, where Docker environnement variables stands. You can also customize Symfony `.env` located inside `apps/symfony` folder.

## Run Locally

Make sure you installed the project first, referring to the [Installation](#Installation) section. Then, if the project has already been setup once, you just have to type
```bash
  make start
```

## Features

### Authentication
- Register a new user
- Login
- Logout
- Activate account
- Ask new password
- Reset password

### Tricks
- Homepage listing snowboard tricks
- Register new tricks
- Read trick's page
- Comment a trick
- Edit a trick

## Tech Stack

**Client:** Alpine.js, TailwindCSS, Yarn

**Server:** PHP 8, Symfony 5.3
