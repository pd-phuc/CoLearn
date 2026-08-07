---
name: onboard
description: Start project onboarding — interview user, generate CLAUDE.md and docs
trigger: /onboard
---

# /onboard — Project Onboarding

## Trigger
User types `/onboard` or says "onboard", "setup project", "bắt đầu dự án"

## Workflow

1. **Load skill**: Read `.agent/skills/project-onboarding/SKILL.md`
2. **Follow Phase 1**: Interview user (4 groups of questions)
3. **Follow Phase 2**: Generate documentation
4. **Follow Phase 3**: Verify and report

## Rules
- Do NOT skip the interview
- Do NOT generate code — only documentation
- Present summary at the end
