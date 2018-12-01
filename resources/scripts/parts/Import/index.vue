<template>
    <v-card class="v-card__import" width="330">
        <v-card-text>
            <div class="v-card__import--wrap">
                <div class="v-card__import--title">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus necessitatibus corrupti voluptas consequatur molestiae. Fugiat laboriosam perferendis repellat numquam dolores voluptatem excepturi, natus, atque dignissimos minus ut ad, eaque libero!
                </div>
                <div class="v-card__import--progress">
                    <v-progress-circular
                        :size="36"
                        :width="6"
                        :value="1"
                        :indeterminate="false"
                        color="teal"
                    ></v-progress-circular>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: 'm-import'
};
</script>
