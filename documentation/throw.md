# JSON example

```json
{
    "type": "THROW",
    "inputs": string,
    "situation": string,
    "slug": string,
    "visibility": {
        "defense": bool
    },
    "frames": {
        "startup": {
            "min": int,
            "max": int
        },
        "hit": {
            "normal": int,
            "wall": {
                "normal": int,
                "splat": int,
                "break": int
            },
            "ukemi": int
        },
        "escape": {
            "normal-hit": int,
            "counter-hit": int
        },
        "after-escape": int
    },
    "behaviors": [
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
    "distances": {
        "range": int,
        "hit": {
            "normal": int,
            "ukemi": int
        },
        "escape": int
    },
    "escapes": [
        string
    ],
    "damages": {
        "normal": int,
        "wall": int,
        "ukemi": int
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

# Video

Stage: Coliseum of fate

Position: left

Player: default skin

Opponent: Kazuya with default skin

Display settings
 * Hide everything except HUD

Practice settings:
 * Ground Technique: Not Set
 * Wake Up: Not Set

Parts:
 * Throw not escaped
 * Throw escaped
 * All behaviors in this order:
   * WALL_SPLAT, stage: Secluded training ground
   * WALL_BREAK, stage: Elegant palace
   * HARD_WALL_BREAK, stage: Coliseum of fate
   * WALL_BOUND, stage: Arena (underground)
   * WALL_BLAST, stage: Midnight siege
   * FLOOR_BREAK, stage: Descent into subconscious
   * FLOOR_BLAST, stage: Into the stratosphere
