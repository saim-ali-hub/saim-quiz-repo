#!/bin/bash

STUDENT="${1:-Ayesha Abdullah}"

for i in {1..34}; do
    FILE="quiz$i/result_quiz${i}_sorted.txt"

    if [[ -f "$FILE" ]]; then
        RESULT=$(grep -iF "$STUDENT" "$FILE" | grep -v '^Result -')

        if [[ -n "$RESULT" ]]; then
            echo "===== quiz${i} ====="
            echo "$RESULT"
        fi
    fi
done

