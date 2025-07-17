#!/bin/bash
while true; do
    echo -n "Enter the First Operand: "
    read operand1

    echo -n "Enter the Second Operand: "
    read operand2
    
    echo "1 -> Addition"
    echo "2 -> Subtraction"
    echo "3 -> Multiplication"
    echo "4 -> Division"
    echo -n "Enter your choice: "
    read choice

    case $choice in
        1)
            operation="Addition"
            result=$((operand1 + operand2))
            ;;
        2)
            operation="Subtraction"
            result=$((operand1 - operand2))
            ;;
        3)
            operation="Multiplication"
            result=$((operand1 * operand2))
            ;;
        4)
            operation="Division"
            if [ "$operand2" -eq 0 ]; then
                echo "Division by zero is not allowed."
                continue
            else
                result=$(echo "scale=2; $operand1 / $operand2" | bc)
            fi
            ;;
        *)
            echo "Invalid choice."
            continue
            ;;
    esac

    echo "$operation"
    echo "The result is: $result"

    echo -n "Do you want to continue? (press 1 to continue, otherwise press any key to quit) "
    read cont
    if [ "$cont" != "1" ]; then
        break
    fi
done
