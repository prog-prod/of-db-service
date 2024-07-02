import {defineStore} from "pinia";
import {ref} from "vue";
import type {ParserInfoData} from "~/layouts/default-layout/config/types";
import axios from "axios";
export const useDashboardStore = defineStore("dashboard", () => {
    const parserData = ref<ParserInfoData>({
        parser: {},
        parserCheckingRegulars: {},
        parserUpdating: {}
    });
    const countActiveParsers = ref<number>(0);
    function setParserData(payload: ParserInfoData) {
        parserData.value = payload;
    }
    async function fetchActiveParsers() {
        let { data: parserData } = await axios.get(route('admin.get-active-parsers'));
        setParserData(parserData.parsers)
        setActiveParsersCount(parserData.countActiveParsers)
    }
    function setActiveParsersCount(payload: number) {
        countActiveParsers.value = payload;
    }
    return {
        countActiveParsers,
        parserData,
        setParserData,
        fetchActiveParsers,
        setActiveParsersCount
    }
});
