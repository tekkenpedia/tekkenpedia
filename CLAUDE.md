# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TekkenPedia is a Symfony 7.0 CLI application (PHP 8.3+) that generates static HTML documentation for Tekken 8 game mechanics. It parses character/move data from JSON files and renders Twig templates into static HTML pages with punish guides, defense options, and move search.

Live site: https://tekkenpedia.github.io/tekkenpedia/

## Commands

### Development
```bash
bin/dev/start          # Start dev environment (Docker)
bin/dev/console <cmd>  # Run Symfony console command in Docker
```

### Main Console Commands
```bash
bin/console move:add <character> <inputs> <used> <defenses> <properties> <blockFramesMin> [blockFramesMax]
bin/console generate:html
bin/console concat:videos
bin/console convert:video:gif
bin/console videos:to:gif
```

### Validation (CI)
```bash
bin/ci/validate   # Runs full validation: phpstan, phpcs, phpdd, composer checks, shellcheck, lint-yaml, unused-scanner
bin/ci/start      # Install deps + warmup cache
```

## Architecture

### Data Flow
JSON data (`data/characters/`) → PHP factories → Readonly domain objects → Twig templates → Static HTML (`docs/`)

### Key Layers
- **Data**: `data/characters/[slug]/character.json` defines character metadata and sections; `data/characters/[slug]/moves/[uuid].json` stores individual move data
- **Parsing/Validation**: `src/Parser/` uses Symfony OptionsResolver to validate all JSON data
- **Factories**: `src/Character/CharacterFactory.php` and `src/Character/Move/MoveFactory.php` build domain objects from validated JSON
- **Domain Models**: `src/Character/` — readonly immutable objects (`Character`, `Attack`, `PowerCrush`, `Throw`)
- **Generators**: `src/Generator/` — render Twig to HTML files (`PunishGenerator`, `DefenseGenerator`, `CharactersListGenerator`)
- **Commands**: `src/Command/` — Symfony Console commands that orchestrate generation and move management

### Move Types
Three move types defined in `MoveTypeEnum`: **Attack** (most common), **PowerCrush**, and **Throw** — each with its own factory, domain class, and options resolver.

### Move Data Structure
Moves contain: inputs, visibility (punish/defense), usage frequency (`UsedEnum`), defenses (`DefenseEnum`), properties (`PropertyEnum` — HIGH/MIDDLE/LOW/SPECIAL_*), frame data (startup/block/hit), damages, distances, behaviors, and sidestep data.

## Code Conventions
- `declare(strict_types=1)` in every PHP file
- Readonly classes for all domain objects
- PHP 8.1+ backed enums with `CreateTrait` and `GetNamesTrait`
- Typed collections via `steevanb/php-collection`
- PSR-4 autoloading under `App\` namespace → `src/`
- Everything in English: code, comments, template labels, documentation. Only git commit messages may be in French.

## GitHub Actions Workflows
- **ci.yml**: Runs validation on every push
- **add-move.yml**: Manual workflow to add a move and create a PR
- **generate-html.yml**: Manual workflow to regenerate all static HTML into `docs/`
