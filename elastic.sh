#!/bin/bash

function show_help() {
    echo "Usage: $0 {command} [index]"
    echo "Commands:"
    echo "  mapping      - Get the mapping of the specified index"
    echo "  search       - Get the first 10 documents from the specified index"
    echo "  fields       - Get all fields with their types for all indices"
    echo "  health       - Get the cluster health"
    echo "  stats        - Get the cluster statistics"
    echo "  reindex      - Reindex documents from source to destination index"
    echo "  delete       - Delete the specified index"
    echo "  help         - Show this help message"
}

if [ -z "$1" ]; then
    show_help
    exit 1
fi

COMMAND=$1
INDEX=$2

case "$COMMAND" in
    mapping)
        if [ -z "$INDEX" ]; then
            echo "Please provide an index name."
            exit 1
        fi
        curl -X GET "localhost:9200/$INDEX/_mapping?pretty"
        ;;
    search)
        if [ -z "$INDEX" ]; then
            echo "Please provide an index name."
            exit 1
        fi
        curl -X GET "localhost:9200/$INDEX/_search?pretty"
        ;;
    fields)
        curl -X GET "localhost:9200/_mapping/field/*?pretty"
        ;;
    health)
        curl -X GET "localhost:9200/_cluster/health?pretty"
        ;;
    stats)
        curl -X GET "localhost:9200/_cluster/stats?pretty"
        ;;
    reindex)
        if [ -z "$INDEX" ]; then
            echo "Please provide the source and destination index names."
            echo "Usage: $0 reindex source_index destination_index"
            exit 1
        fi
        DEST_INDEX=$3
        if [ -z "$DEST_INDEX" ]; then
            echo "Please provide the destination index name."
            echo "Usage: $0 reindex source_index destination_index"
            exit 1
        fi
        curl -X POST "localhost:9200/_reindex?pretty" -H 'Content-Type: application/json' -d"
        {
            \"source\": {
                \"index\": \"$INDEX\"
            },
            \"dest\": {
                \"index\": \"$DEST_INDEX\"
            }
        }"
        ;;
    delete)
        if [ -z "$INDEX" ]; then
            echo "Please provide an index name."
            exit 1
        fi
        curl -X DELETE "localhost:9200/$INDEX?pretty"
        ;;
    help)
        show_help
        ;;
    *)
        echo "Unknown command: $COMMAND"
        show_help
        exit 1
        ;;
esac
