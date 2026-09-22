#!/bin/bash

RESULT_FILE="result_dated_$(date +%m.%d.%Y)"

for i in {1..23}; do
    (cd "quiz$i" && ./script_sorting.sh)
    cat "quiz$i/result_quiz${i}_sorted.txt" >> "$RESULT_FILE"
done
