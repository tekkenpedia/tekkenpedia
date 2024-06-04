# JSON example

```json
{
    "master": uuidv4,
    "inputs": string,
    "situation": string,
    "heat": bool,
    "extends": uuidv4,
    "slug": string,
    "visibility": {
        "defense": bool
    },
    "property": "HIGH|MIDDLE",
    "damage-reduction": int,
    "frames": {
        "startup": {
            "min": int,
            "max": int
        },
        "absorption": {
            "min": int,
            "max": int
        },
        "after-absorption": {
            "block": int
        },
        "block": {
            "min": int,
            "max": int
        },
        "normal-hit": int,
        "counter-hit": int
    },
    "behaviors": {
        "block": [
            "WALL_SPLAT",
            "WALL_BREAK",
            "HARD_WALL_BREAK",
            "WALL_BOUND",
            "WALL_BLAST",
            "FLOOR_BREAK",
            "FLOOR_BLAST",
            "KNOCKDOWN",
            "AIR",
            "DELETE_RECOVERABLE_LIFE_BAR",
            "HEAT_BURST",
            "HEAT_ENGAGER",
            "OPPONENT_CROUCH"
        ],
        "normal-hit": [
            "WALL_SPLAT",
            "WALL_BREAK",
            "HARD_WALL_BREAK",
            "WALL_BOUND",
            "WALL_BLAST",
            "FLOOR_BREAK",
            "FLOOR_BLAST",
            "KNOCKDOWN",
            "AIR",
            "DELETE_RECOVERABLE_LIFE_BAR",
            "HEAT_BURST",
            "HEAT_ENGAGER",
            "OPPONENT_CROUCH"
        ],
        "counter-hit": [
            "WALL_SPLAT",
            "WALL_BREAK",
            "HARD_WALL_BREAK",
            "WALL_BOUND",
            "WALL_BLAST",
            "FLOOR_BREAK",
            "FLOOR_BLAST",
            "KNOCKDOWN",
            "AIR",
            "DELETE_RECOVERABLE_LIFE_BAR",
            "HEAT_BURST",
            "HEAT_ENGAGER",
            "OPPONENT_CROUCH"
        ]
    },
    "distances": {
        "range": int,
        "block": {
            "min": int,
            "max": int
        },
        "normal-hit": {
            "min": int,
            "max": int
        },
        "counter-hit": {
            "min": int,
            "max": int
        }
    },
    "steps": {
        "ssl": "EASY|MEDIUM|HARD|IMPOSSIBLE",
        "swl": "EASY|MEDIUM|HARD|IMPOSSIBLE",
        "ssr": "EASY|MEDIUM|HARD|IMPOSSIBLE",
        "swr": "EASY|MEDIUM|HARD|IMPOSSIBLE"
    },
    "damages": {
        "normal-hit": int,
        "counter-hit": int
    },
    "comments": [
        {
            "comment": string,
            "type": "NORMAL|DEFENSE|STRENGTH",
            "width": "ONE|TWO|THREE|FOUR"
        }
    ]
}
```
